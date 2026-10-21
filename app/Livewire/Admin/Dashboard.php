<?php

namespace App\Livewire\Admin;

use App\Models\Agency;
use App\Models\Airline;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Location;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{

    public $bookings_date;
    public $year;
    public $filter = [];
    public $topAgencies = [];
    public $filtered = false;
    // Rebuilt on every render: the computed ticket/room counts don't survive Livewire's model rehydration.
    protected $bookingsToday = [];
    public $topWhiteLabels = [];
    public $airlines = [];
    public $locations = [];
    public $labels = [];
    public $remainingSeatsSearches = [];
    public $message = '';
    public $remainingSeatAirline;
    public $remaining = [];

    public function addCriteria()
    {
        // Define a new array containing the keys and their initial values
        $newCriteria = [
            'remainingSeatsStartDate' => '',
            'remainingSeatsEndDate' => '',
            'remainingSeatAirline' => '',
            'remainingSeatFrom' => '',
            'remainingSeatTo' => '',
            'remainingSeatType' => 'oneway'
        ];

        // Add the new criteria array to the $remainingSeatsSearches array
        $this->remainingSeatsSearches[] = $newCriteria;
    }

    public function mount()
    {
        $this->year = date('Y');
        $this->bookings_date = 'today';
        $this->airlines = Airline::orderBy('name')->pluck('name', 'id')->toArray();
        $this->locations = Location::where('active', 1)->pluck('airport_code', 'airport_code')->toArray();
        $this->labels = Agency::pluck('name', 'id')->toArray();
        $this->addCriteria();
    }

    public function render()
    {
        $this->filtered ? $this->loadFilteredBookings() : $this->bookingsToday();

        return view('livewire.admin.dashboard', ['bookingsToday' => $this->bookingsToday])->layout('layouts.admin');
    }

    public function getRemainingSeats()
    {
        $this->message = '';
        $remains = [];
        foreach($this->remainingSeatsSearches as $remainingSeat) {
            $remains[] = [
                'search' => $remainingSeat,
                'results' => $this->getRemainings($remainingSeat)
            ];
        }
        $this->remaining = $remains;
    }

    public function getRemainings($values)
    {
        $remaining = Flight::where(function ($query) use ($values) {
            $query->where(function ($q) use ($values) {
                $q->where('dep_from_code', $values['remainingSeatFrom'])
                    ->where('dep_to_code', $values['remainingSeatTo']);
            })
                ->orWhere(function ($q) use ($values) {
                    $q->where('dep_from_code', $values['remainingSeatTo'])
                        ->where('dep_to_code', $values['remainingSeatFrom']);
                });
        })
            ->whereBetween('dep_date', [$values['remainingSeatsStartDate'], $values['remainingSeatsEndDate']])
            ->where('type', $values['remainingSeatType']);

        if (isset($values['remainingSeatAirline'])) {
            $remaining->where('airline_id', $values['remainingSeatAirline']);
        }

        $remaining->withCount(['tickets as tickets_count' => function ($q) {
            $q->where('title', '<>', 'Infant.');
        }])->withSum('prices as all_seats', 'seats');
        $remaining->where('active', 1);
        $results = $remaining->orderBy('dep_date')->get();
        if($values['remainingSeatType'] == 'fixed') {
            return [
                'departure' => array_values($results->toArray()),
                'return'    => []
            ];
        } else {
            return [
                'departure' => array_values($results->where('dep_from_code', $values['remainingSeatFrom'])->toArray()),
                'return' => array_values($results->where('dep_from_code', $values['remainingSeatTo'])->toArray())
            ];
        }
    }

    protected function getBookingsByYear()
    {
        if (auth()->user()->type == 'white-admin') {
            $bookings = Booking::selectRaw('MONTH(created_at) as month, count(*) as count')
                ->whereYear('created_at', $this->year)
                ->where('agency_id', auth()->user()->agency_id)
                ->groupBy(DB::raw("MONTH(created_at)"))
                ->get();
        } else {
            $bookings = Booking::selectRaw('MONTH(created_at) as month, count(*) as count')
                ->whereYear('created_at', $this->year)
                ->groupBy(DB::raw("MONTH(created_at)"))
                ->get();
        }

        return $bookings->map(function ($item) {
            return [Carbon::createFromFormat('m', $item->month)->format('M') => $item->count];
        });
    }

    public function loadTopAgencies()
    {
        $top = User::select('users.id', DB::raw('COUNT(DISTINCT tickets.full_name) as ticket_count'))
            ->join('bookings', 'bookings.user_id', '=', 'users.id')
            ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
            ->where('bookings.agency_id', '=', auth()->user()->agency_id)
            ->groupBy('users.id')
            ->orderByDesc('ticket_count')
            ->take(10);
        $this->topAgencies = $top->get();
    }

    public function loadTopWhiteLabels()
    {
        $top = Booking::where('agency_id', '!=', auth()->user()->agency_id)
            ->withCount('tickets')
            ->orderBy('tickets_count', 'DESC')
            ->with('agency')
            ->limit(10);
        $this->topWhiteLabels = $top->get();
    }

    public function bookingsToday($select_date = null)
    {
        if ($select_date) {
            $date = Carbon::parse($select_date);
        } else {
            $date = Carbon::today();
        }
        $this->bookingsToday = Booking::withTrashed()
            ->where('agency_id', auth()->user()->agency_id)
            ->whereDate('created_at', $date)
            ->with(['tickets' => function($q) {
                $q->withTrashed();
            }])
            ->with(['rooms' => function($q) {
                $q->withTrashed();
            }])
            ->get();

        $this->bookingsToday->each(function ($booking) {
            // Count tickets grouped by full_name
            $ticketGroups = $booking->tickets->groupBy('full_name');
            $booking->tickets_count = $ticketGroups->count();

            // Count rooms including trashed
            $booking->rooms_count = $booking->rooms->count();
        });
    }

    public function search()
    {
        $this->filtered = true;
    }

    protected function loadFilteredBookings()
    {
        if (isset($this->filter['label'])) {
            $bookings = Booking::where('agency_id', $this->filter['label']);
        } else {
            $bookings = Booking::where('agency_id', auth()->user()->agency_id);
        }
        if (isset($this->filter['from_date'])) {
            $from = Carbon::parse($this->filter['from_date']);
            $bookings->where('created_at', '>=', $from);
        }
        if (isset($this->filter['to_date'])) {
            $to = Carbon::parse($this->filter['to_date']);
            $bookings->where('created_at', '<=', $to);
        }
        if (isset($this->filter['status'])) {
            if($this->filter['status'] == 'Deleted') {
                $bookings->whereNotNull('deleted_at');
            } else {
                $bookings->where('status', $this->filter['status']);
            }
        }
        if (isset($this->filter['departure'])) {
            $bookings->where('dep_from_code', $this->filter['departure']);
        }
        if (isset($this->filter['destination'])) {
            $bookings->where('dep_to_code', $this->filter['destination']);
        }
        if (isset($this->filter['airline'])) {
            // Bookings have no airline_id; match on the airline of the booked departure flight.
            $bookings->whereIn('dep_flight_id', Flight::where('airline_id', $this->filter['airline'])->select('id'));
        }
        $this->bookingsToday = $bookings->withTrashed(isset($this->filter['status']) && $this->filter['status'] == 'Deleted')
            ->with(['tickets' => function($q) {
                $q->withTrashed();
            }, 'rooms' => function($q) {
                $q->withTrashed();
            }])
            ->get();
        $this->bookingsToday->each(function ($booking) {
            // Count tickets grouped by full_name
            $ticketGroups = $booking->tickets->groupBy('full_name');
            $booking->tickets_count = $ticketGroups->count();

            // Count rooms including trashed
            $booking->rooms_count = $booking->rooms->count();
        });
    }
}

<?php

namespace App\Livewire\Admin\Flight;

use App\Livewire\WithConfirmation;
use App\Models\Airline;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Flight;
use App\Models\Location;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithConfirmation;

    protected $paginationTheme = 'bootstrap';

    protected $alphabet;
    public $ret_dep_date;
    public $synchronizing = false;
    public $syncFlightId = null; // For modal confirmation

    public $sortBy;

    public $sortDirection;

    public int $perPage;

    public $pricing = [];
    public $business_pricing = [];

    public array $selected = [];
    public array $selectedCategories = [];

    public $airlines = [];
    public $locations = [];
    public $types = [];
    public $range_from_date;
    public $range_to_date;
    public $airline_id = '';
    public $dep_from_code = '';
    public $dep_to_code = '';
    public $dep_date = '';
    public $dep_arr_date = '';
    public $ret_date = '';
    public $dep_ret_date = '';
    public $type = '';
    public $dep_flight_number = '';
    public $ret_flight_number = '';
    public $available_seats = 0;
    public $keyIndex;
    public $flights_ids;
    public array $paginationOptions;

    public $categories;
    public bool $selectAll;
    public array $supplements = [
        'oneway_supplements'        => null,
        'multi_supplements'         => null,
        'combinable_supplements'    => null,
    ];

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->selectAll = false;
        $this->types             = ['oneway' => 'Oneway', 'fixed' => 'Fixed'];
        $this->perPage           = 50;
        $this->sortDirection     = 'DESC';
        $this->sortBy            = 'created_at';
        $this->paginationOptions = config('project.pagination.options');
        $this->airlines          = Airline::orderBy('name')->pluck('name', 'id')->toArray();
        $this->locations         = Location::where('active', 1)->orderBy('city')->pluck('city', 'airport_code')->toArray();
        $this->categories        = Category::get();
        $this->updatePrices();
    }

    public function render()
    {
        $query = Flight::with(['category', 'depFrom', 'depTo', 'retFrom', 'retTo', 'contract', 'prices', 'airline']);
        $query->withCount([
            'tickets as tickets_count' => function($q) { $q->where('title', '!=', 'Infant.'); },
            'tickets as economy_tickets_count' => function($q) { $q->where('title', '!=', 'Infant.')->where('cabin', 'economy'); },
            'tickets as business_tickets_count' => function($q) { $q->where('title', '!=', 'Infant.')->where('cabin', 'business'); }
        ]);

        if($this->airline_id)           $query->where('airline_id', $this->airline_id);
        if($this->dep_from_code)        $query->where('dep_from_code', $this->dep_from_code);
        if($this->dep_to_code)          $query->where('dep_to_code', $this->dep_to_code);
        if($this->dep_date)             $query->where('dep_date', $this->dep_date);
        if($this->dep_ret_date)         $query->where('dep_ret_date', $this->dep_ret_date);
        if($this->dep_arr_date)         $query->where('dep_arr_date', $this->dep_arr_date);
        if($this->ret_date)             $query->where('ret_date', $this->ret_date);
        if($this->type)                 $query->where('type', $this->type);
        if($this->dep_flight_number)    $query->where('dep_flight_number', $this->dep_flight_number);
        if($this->ret_flight_number)    $query->where('ret_flight_number', $this->ret_flight_number);
        if($this->available_seats)      $query->has('tickets', '<', DB::raw('flights.seats'));
        if($this->range_from_date)      $query->where('dep_date', '>=',$this->range_from_date);
        if($this->range_to_date)        $query->where('dep_date', '<=',$this->range_to_date);
        if($this->sortBy)               $query->orderBy($this->sortBy, $this->sortDirection);

        $flights = $query->paginate($this->perPage);
        $this->flights_ids = $flights->pluck('id')->map(function ($id) {
            return (string) $id;
        })->toArray();
        return view('livewire.admin.flight.index', compact('flights', 'query'))->layout('layouts.admin');
    }

    public function updatedSelectAll($k, $v)
    {
        if($k == 1)
        {
            $this->selected = $this->flights_ids;
        }
    }

    public function updatePrices()
    {
        $this->pricing = [
            'prices' => [],
            'business_pricing' => []
        ];
    }

    public function updateSupplements()
    {
        $this->supplements = [
            'oneway_supplements'        => null,
            'multi_supplements'         => null,
            'combinable_supplements'    => null,
        ];
    }

    public function savePrices()
    {

        $economy = [
            'L' => 1,
            'M' => 2,
            'Y' => 3
        ];

        $business = [
            'Z' => 1,
            'K' => 2,
            'J' => 3
        ];

        foreach($this->selected as $id):
            foreach($this->pricing['prices'] as $class => $prices):
                foreach($prices as $seatType => $singlePrice):
                    if(is_numeric($singlePrice)) {
                        Price::where([
                            'flight_id'     => $id,
                            'class'         => $class
                        ])->update([
                            'position' => $economy[$class],
                            $seatType => $singlePrice,
                            'type' => 'economy'
                        ]);
                    }
                endforeach;
            endforeach;

            foreach($this->pricing['business_pricing'] as $class => $prices):
                foreach($prices as $seatType => $singlePrice):
                    if(is_numeric($singlePrice)) {
                        Price::updateOrCreate([
                            'flight_id'     => $id,
                            'class'         => $class
                        ])->update([
                            'position' => $business[$class],
                            $seatType => $singlePrice,
                            'type' => 'business'
                        ]);
                    }
                endforeach;
            endforeach;

            $flight = Flight::with('prices')->find($id);

            $seats = 0;
            $businessSeats = 0;

            foreach ($flight->prices as $price) {
                $seats += $price->seats;
                if($price->type == 'business') {
                    $businessSeats += $price->seats;
                }
            }
            $flight->update(['seats' => $seats, 'business_seats' => $businessSeats]);
        endforeach;
        $this->dispatch('close-modals');
    }

    public function saveSupplements()
    {
        $update = [];
        if(is_numeric($this->supplements['oneway_supplements']))        $update['oneway_supplements']       = $this->supplements['oneway_supplements'];
        if(is_numeric($this->supplements['multi_supplements']))         $update['multi_supplements']        = $this->supplements['multi_supplements'];
        if(is_numeric($this->supplements['combinable_supplements']))    $update['combinable_supplements']   = $this->supplements['combinable_supplements'];
        if(count($update)) {
            foreach($this->selected as $id):
                $flight = Flight::find($id);
                $flight->update($update);
            endforeach;
        }
        $this->dispatch('close-modals');
    }

    public function saveCategories()
    {
        foreach($this->selected as $selection):
            $flight = Flight::where('id', $selection)->first();
            $flight->category()->sync($this->selectedCategories);
        endforeach;
        $this->dispatch('close-modals');
    }

    public function deleteSelected()
    {
        Flight::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }

    public function activate()
    {
        Flight::whereIn('id', $this->selected)->update(['active' => 1]);
    }

    public function deactivate()
    {
        Flight::whereIn('id', $this->selected)->update(['active' => 0]);
    }

    public function delete(Flight $flight)
    {
        $flight->delete();
    }

    public function confirmSync($id)
    {
        $this->syncFlightId = $id;
    }

    public function cancelSync()
    {
        $this->syncFlightId = null;
    }

    public function sync($id)
    {
        $this->syncFlightId = null; // hide modal after sync

        $flight = Flight::query()->with(['airline'])->find($id);

        DB::transaction(function () use ($flight, $id) {

            $bookings = Booking::query()
                ->where('dep_flight_id', $id)
                ->orWhere('ret_flight_id', $id)
                ->with('tickets')
                ->get();

            foreach ($bookings as $booking) {
                $updated = false;
                if ($booking->dep_flight_id == $id) {
                    $booking->dep_date = Carbon::parse($flight->dep_date)->format('Y-m-d');
                    $booking->dep_time = $flight->dep_time;
                    $booking->dep_arr_time = $flight->dep_arr_time;
                    $booking->dep_airline_name = $flight->airline->name;
                    $booking->dep_airline_logo = $flight->airline->thumbnail;
                    $booking->dep_airline_code = $flight->airline->icao;
                    $booking->dep_flight_number = $flight->dep_flight_number;
                    $updated = true;
                }

                if ($booking->ret_flight_id == $id) {
                    if($flight->type == 'fixed') {
                        $booking->ret_date = Carbon::parse($flight->ret_dep_date)->format('Y-m-d');
                        $booking->ret_time = $flight->ret_dep_date;
                        $booking->ret_dep_time = $flight->ret_dep_time;
                        $booking->ret_arr_time = $flight->ret_arr_time;
                        $booking->ret_airline_name = $flight->airline->name;
                        $booking->ret_airline_logo = $flight->airline->thumbnail;
                        $booking->ret_airline_code = $flight->airline->icao;
                        $booking->ret_flight_number = $flight->ret_flight_number;
                    } else {
                        $booking->ret_date = Carbon::parse($flight->dep_date)->format('Y-m-d');
                        $booking->ret_time = $flight->dep_time;
                        $booking->ret_dep_time = $flight->dep_time;
                        $booking->ret_arr_time = $flight->dep_arr_time;
                        $booking->ret_airline_name = $flight->airline->name;
                        $booking->ret_airline_logo = $flight->airline->thumbnail;
                        $booking->ret_airline_code = $flight->airline->icao;
                        $booking->ret_flight_number = $flight->dep_flight_number;
                    }
                    $updated = true;
                }

                if ($updated) {
                    $booking->save();
                }

                foreach ($booking->tickets as $ticket) {
                    $ticketUpdated = false;

                    if ($ticket->flight_id == $id) {
                        $ticket->dep_date = Carbon::parse($flight->dep_date)->format('Y-m-d');
                        $ticket->dep_time = $flight->dep_time;
                        $ticket->dep_arr_time = $flight->dep_arr_time;
                        $ticketUpdated = true;

                        // Assuming you also want to update return flight details for the ticket
                        if ($flight->ret_dep_date && $flight->ret_dep_time && $flight->ret_arr_time) {
                            $ticket->ret_date = Carbon::parse($flight->ret_dep_date)->format('Y-m-d');
                            $ticket->ret_time = $flight->ret_dep_time;
                            $ticket->ret_arr_time = $flight->ret_arr_time;
                        }
                    }

                    if ($ticketUpdated) {
                        $ticket->save();
                    }
                }
            }
        });
    }

}

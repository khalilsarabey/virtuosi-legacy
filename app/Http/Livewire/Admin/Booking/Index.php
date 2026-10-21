<?php

namespace App\Http\Livewire\Admin\Booking;

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\Connections\Api\ApiBookingController;
use App\Http\Controllers\Connections\Client\ApiClientBookingController;
use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Mail\DeleteBookingByIdMail;
use App\Mail\DeleteBookingMail;
use App\Mail\NewBookingEmail;
use App\Models\Airline;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Location;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    protected $paginationTheme = 'bootstrap';

    public $deleteModal;
    public $passengersToDelete = [];
    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public $types = [];

    public $locations = [];

    public $agencies = [];

    public $airlines = [];

    public $filters = [];

    public $type;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];
    public $categories = [];

    protected $listeners = [
        'passengerChecked' => 'addPassengerToDelete',
        'passengerUnchecked' => 'removePassengerFromDelete',
    ];

    public function addPassengerToDelete($passengerId)
    {
        if (!in_array($passengerId, $this->passengersToDelete)) {
            $this->passengersToDelete[] = $passengerId;
        }
    }

    public function removePassengerFromDelete($passengerId)
    {
        // Ensure $this->passengersToDelete is always an array
        if (!is_array($this->passengersToDelete)) {
            $this->passengersToDelete = [];
        }

        if (($key = array_search($passengerId, $this->passengersToDelete)) !== false) {
            unset($this->passengersToDelete[$key]);
        }

        // Perform the removal if $passengersToDelete is indeed an array
        if (($key = array_search($passengerId, $this->passengersToDelete)) !== false) {
            unset($this->passengersToDelete[$key]);
        }
    }


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
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Booking())->orderable;
        $this->types             = ['oneway' => 'Oneway', 'roundtrip' => 'Roundtrip', 'fixed' => 'Fixed', 'multi' => 'Multiple Destinations'];
        $this->airlines          = Airline::orderBy('name')->pluck('name', 'id')->toArray();
        $this->locations         = Location::where('active', 1)->orderBy('city')->pluck('city', 'city')->toArray();
        if(auth()->user()->type == 'white-admin') {
            $this->agencies      = User::orderBy('name')->where('agency_id', auth()->user()->agency_id)->pluck('name', 'id')->toArray();
        } else if (auth()->user()->type == 'user') {
            $this->agencies = [];
        } else {
            $this->agencies      = User::orderBy('name')->pluck('name', 'id')->toArray();
        }
        $this->categories        = Category::orderBy('name')->pluck('name', 'id')->toArray();
        $this->filters           = ['packages' => 0, 'show_only' => 'active'];
    }

    public function updatePassengersToDelete(array $passengerIds, bool $isChecked)
    {
        if ($isChecked) {
            foreach ($passengerIds as $id) {
                if (!in_array($id, $this->passengersToDelete)) {
                    $this->passengersToDelete[] = $id;
                }
            }
        } else {
            $this->passengersToDelete = array_diff($this->passengersToDelete, $passengerIds);
        }
    }


    public function confirmBooking($id)
    {
        $booking = Booking::query()->with('user')->where('id', $id)->first();
        $booking->update(['status' => 'Confirmed']);
        try {
            Mail::queue(new NewBookingEmail($booking->id, $booking->user?->email ?? 's.samman@samadtours.com', 'Your booking was confirmed.'));
        } catch(\Exception $e) {

        }
    }

    public function render()
    {

        $query = Ticket::with([
            'booking' => function($q) {
                $q->withTrashed();
            },
            'booking.user', 'flight', 'booking.agency'
        ]);

        if(count($this->filters)) {
            if(isset($this->filters['show_only']) && $this->filters['show_only'] == 'deleted') {
                $query->onlyTrashed();
            }
            if(isset($this->filters['airline'])) {
                $query->whereHas('flight.airline', function ($query) {
                    $query->where('id', $this->filters['airline']);
                });
            }
            if(isset($this->filters['name'])) {
                $query->where('full_name', 'LIKE', '%' . $this->filters['name'] . '%');
            }
            if(isset($this->filters['created_at'])) {
                $query->whereDate('created_at', '=', $this->filters['created_at']);
            }
            $query->whereHas('booking', function($q) {
                if(isset($this->filters['show_only']) && $this->filters['show_only'] == 'deleted') {
                    $q->withTrashed();
                }
                if(auth()->user()->type == 'white-admin') {
                    $q->where('agency_id', auth()->user()->agency_id);
                } else if(auth()->user()->type == 'user') {
                    $q->where('user_id', auth()->user()->id);
                }
                if(isset($this->filters['source'])) {
                    $q->where('source', '=', $this->filters['source']);
                }
                if(isset($this->filters['packages']) && $this->filters['packages'] == 1) {
                    $q->where(function ($q) {
                        $q->whereNotNull('hotel_id')->orWhere('hotel_name', '!=', '');
                    });
                } elseif(isset($this->filters['packages']) && $this->filters['packages'] == 0) {
                    $q->where(function ($q) {
                        $q->whereNull('hotel_id')->orWhereNull('hotel_name');
                    });
                } else {
                    $q->where(function ($q) {
                        $q->whereNull('hotel_id')->orWhereNull('hotel_name');
                    });
                }
                if(isset($this->filters['reference']) && !empty($this->filters['reference']) && $this->filters['reference'] != '') {
                    $q->where('id', getLastPositiveNumber($this->filters['reference']));
                }
                if(isset($this->filters['category'])) {
                    $q->where('category_id', $this->filters['category']);
                }
                if(isset($this->filters['user_id'])) {
                    $q->where('user_id', $this->filters['user_id']);
                }
                if(isset($this->filters['type'])) {
                    $q->where('type', $this->filters['type']);
                }
                if(isset($this->filters['ret_flight_number']) && !empty($this->filters['ret_flight_number']) && $this->filters['ret_flight_number'] != '') {
                    $q->where('ret_flight_number', $this->filters['ret_flight_number']);
                }
                if(isset($this->filters['dep_flight_number']) && !empty($this->filters['dep_flight_number']) && $this->filters['dep_flight_number'] != '') {
                    $q->where('dep_flight_number', $this->filters['dep_flight_number']);
                }
                if(isset($this->filters['dep_from'])) {
                    $q->where('dep_from_city', $this->filters['dep_from']);
                }
                if(isset($this->filters['dep_to'])) {
                    $q->where('dep_to_city', $this->filters['dep_to']);
                }
                if(isset($this->filters['ret_from'])) {
                    $q->where('ret_from_city', $this->filters['ret_from']);
                }
                if(isset($this->filters['ret_to'])) {
                    $q->where('ret_to_city', $this->filters['ret_to']);
                }
                if(isset($this->filters['dep_date'])) {
                    $q->where('dep_date', $this->filters['dep_date']);
                }
                if(isset($this->filters['ret_dep_date'])) {
                    $q->where('ret_date', $this->filters['ret_dep_date']);
                }
            });
        } else {
            $query->whereHas('booking', function($q) {
                if(auth()->user()->type == 'white-admin') {
                    $q->where('agency_id', auth()->user()->agency_id);
                } else if(auth()->user()->type == 'user') {
                    $q->where('user_id', auth()->user()->id);
                }
                return $q;
            });
        }

        $query->orderBy($this->sortBy, $this->sortDirection);
        $tickets = $query->paginate($this->perPage);

        return view('livewire.admin.booking.index', compact('query', 'tickets'))->layout('layouts.admin');
    }

    public function initializeDelete($id)
    {
        $this->deleteModal = Booking::where('id', $id)->with(['tickets', 'hotel', 'rooms'])->first();
    }

//    public function updateFromSource($local, $source)
//    {
//        ApiClientBookingController::sendBookingRequest($request->all());
//    }

    public function deleteSelection()
    {
        DB::beginTransaction();
        try {
            // Loop through the tickets and delete the selected passengers
            foreach ($this->deleteModal->tickets as $ticket) {
                if (in_array($ticket->id, $this->passengersToDelete)) {
                    $ticket->delete();
                }
            }
            $available = Ticket::where('booking_id', $this->deleteModal->id)->count();
            if($available == 0) {
                Booking::where('id', $this->deleteModal->id)->delete();
            }
            DB::commit();
            Mail::send(new DeleteBookingByIdMail($this->deleteModal, $this->passengersToDelete));
            session()->flash('success', 'Selected passengers deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete selected passengers.');
        }

        // Resetting the deleteModal and passengersToDelete variables
        $this->deleteModal          = null;
        $this->passengersToDelete   = [];
    }

    public function deleteBooking()
    {
        DB::beginTransaction();
        try {
            $booking = $this->deleteModal;
            $bookingToDelete = $booking;
            // Delete tickets associated with the booking
            foreach ($booking->tickets as $ticket) {
                $ticket->delete();
            }

            // Delete rooms associated with the booking
            foreach ($booking->rooms as $room) {
                $room->delete();
            }

            // Delete the booking itself
            $booking->delete();

            DB::commit();
            session()->flash('success', 'Booking deleted successfully.');
            Mail::send(new DeleteBookingByIdMail($bookingToDelete));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete booking.');
        }

        // Resetting the deleteModal variable
        $this->deleteModal = null;
    }

    public function delete($ticket_id)
    {
        $ticket_to_delete = Ticket::where(['id' => $ticket_id])->with(['flight', 'booking', 'booking.user'])->first();
        try {
            Mail::send(new DeleteBookingMail($ticket_to_delete));
        } catch(\Exception $e) {}

        if($ticket_to_delete) {
            $booking_id = $ticket_to_delete->booking_id;
            $ticket_to_delete->delete();
            $available = Ticket::where('booking_id', $booking_id)->count();
            if($available == 0) {
                Booking::where('id', $booking_id)->delete();
            }
        }
    }
}

<?php

namespace App\Livewire\Admin\Booking;

use App\Livewire\WithConfirmation;
use App\Livewire\WithSorting;
use App\Mail\DeleteBookingByIdMail;
use App\Mail\DeleteBookingMail;
use App\Models\Airline;
use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\Category;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\RoomType;
use App\Models\Season;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Hotels extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    protected $paginationTheme = 'bootstrap';

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public $seasons = [];

    public $locations = [];

    public $agencies = [];

    public $hotels = [];

    public $filters = [];

    public $type;
    public $types = [];

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
        $this->seasons           = Season::orderBy('name')->pluck('name', 'id')->toArray();
        $this->hotels            = Hotel::orderBy('name')->pluck('name', 'id')->toArray();
        $this->types             = RoomType::orderBy('name')->pluck('name', 'id')->toArray();
        $this->locations         = Location::where('active', 1)->orderBy('city')->pluck('city', 'city')->toArray();
        if(auth()->user()->type == 'white-admin') {
            $this->agencies      = User::orderBy('name')->where('agency_id', auth()->user()->agency_id)->pluck('name', 'id')->toArray();
        } else if (auth()->user()->type == 'user') {
            $this->agencies = [];
        } else {
            $this->agencies      = User::orderBy('name')->where('type', '!=', 'white')->pluck('name', 'id')->toArray();
        }
        $this->categories        = Category::orderBy('name')->pluck('name', 'id')->toArray();
        $this->filters           = ['packages' => 0];
    }

    public function confirmBooking($id)
    {
        Booking::where('id', $id)->update(['status' => 'Confirmed']);
    }

    public function render()
    {
        $query = Booking::query();
        if(isset($this->filters['packages']) && $this->filters['packages'] == 1) {
            $query->whereIn('type', ['roundtrip']);
            $query->whereHas('rooms');
        }
        if(isset($this->filters['packages']) && $this->filters['packages'] == 0) {
            $query->where('type', 'hotel');
        }
        $query->with(['rooms.room', 'rooms.room.room_type']);
        $query->orderBy($this->sortBy, $this->sortDirection);
        if(count($this->filters)) {
            if(isset($this->filters['user_id'])) {
                $query->where('user_id', $this->filters['user_id']);
            }
            if(isset($this->filters['hotel']) || isset($this->filters['type']) || isset($this->filters['check_in']) || isset($this->filters['check_out'])) {
                $query->whereHas('rooms', function ($q) {
                    if(isset($this->filters['hotel'])):
                        $q->where('hotel_id', $this->filters['hotel']);
                    endif;
                    if(isset($this->filters['check_in'])):
                        $q->whereDate('check_in', '>=', $this->filters['check_in']);
                    endif;
                    if(isset($this->filters['check_out'])):
                        $q->whereDate('check_out', '<=', $this->filters['check_out']);
                    endif;
                    if(isset($this->filters['type'])):
                        $q->whereHas('room', function ($q) {
                            $q->where('room_type_id', $this->filters['type']);
                        });
                    endif;
                });
            }
            if(isset($this->filters['reference']) && !empty($this->filters['reference']) && $this->filters['reference'] != '') {
                $query->where('reference', $this->filters['reference']);
            }
        }
        $bookings = $query->paginate($this->perPage);
        return view('livewire.admin.booking.hotels', compact('query', 'bookings'))->layout('layouts.admin');
    }

    public function delete($booking_id)
    {
        DB::transaction(function () use ($booking_id) {
            // Fetch the booking with its related tickets and rooms
            $booking = Booking::where('id', $booking_id)->with(['rooms', 'tickets'])->first();

            if ($booking) {
                // Delete related tickets and rooms in bulk
                $booking->tickets()->delete();
                $booking->rooms()->delete();

                // Delete the booking itself
                $booking->delete();

                // Send the deletion email after committing the transaction
                Mail::send(new DeleteBookingByIdMail($booking));
            }
        });
    }
}

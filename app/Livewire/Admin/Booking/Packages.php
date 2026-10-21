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
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Packages extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    protected $paginationTheme = 'bootstrap';

    public int $perPage;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public $types = [];

    public $locations = [];

    public $agencies = [];

    public $airlines = [];

    public $filters = [];

    public $type;

    public $hotels_list = [];

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
        $this->hotels_list       = Hotel::query()->orderBy('name')->pluck('name', 'id')->toArray();

        if(auth()->user()->type == 'white-admin') {
            $this->agencies      = User::orderBy('name')->where('agency_id', auth()->user()->agency_id)->pluck('name', 'id')->toArray();
        } else if (auth()->user()->type == 'user') {
            $this->agencies = [];
        } else {
            $this->agencies      = User::orderBy('name')->where('type', '!=', 'white')->pluck('name', 'id')->toArray();
        }

        $this->filters           = [];
    }

    public function confirmBooking($id)
    {
        Booking::where('id', $id)->update(['status' => 'Confirmed']);
    }

    public function render()
    {

        $query = Booking::query();
        $query->where('type', 'roundtrip');
        $query->with('rooms');
        $query->where(function ($q) {
            $q->whereNotNull('hotel_id')
              ->orWhere('hotel_name', '!=', '');
        });
        if(count($this->filters)) {
            if(isset($this->filters['reference']) && !empty($this->filters['reference'])) {
                $query->where('reference', $this->filters['reference']);
            }
            if(isset($this->filters['user_id'])) {
                $query->where('user_id', $this->filters['user_id']);
            }
            if(isset($this->filters['hotel_id'])) {
                $query->where('hotel_id', $this->filters['hotel_id']);
            }
            if(isset($this->filters['dep_date'])) {
                $query->where('dep_date','>=', $this->filters['dep_date']);
            }
            if(isset($this->filters['ret_date'])) {
                $query->where('ret_date', '<=', $this->filters['dep_date']);
            }
            if(isset($this->filters['from_created_at'])) {
                $query->where('created_at', '>=', $this->filters['from_created_at']);
            }
            if(isset($this->filters['to_created_at'])) {
                $query->where('created_at', '<=', $this->filters['to_created_at']);
            }
        }

        if(auth()->user()->type == 'white-admin') {
            $query->where('agency_id', auth()->user()->agency_id);
        } else if(auth()->user()->type == 'user') {
            $query->where('user_id', auth()->user()->id);
        }
        $query->orderBy($this->sortBy, $this->sortDirection);
        $bookings = $query->paginate($this->perPage);
        return view('livewire.admin.booking.packages', compact('query', 'bookings'))->layout('layouts.admin');
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

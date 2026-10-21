<?php

namespace App\Http\Livewire\Admin\Booking;

use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\Hotel;
use App\Models\Location;
use Livewire\Component;

class EditHotel extends Component
{
    public $editing = 'guests';
    protected $queryString = ['editing' => ['except' => 'guests']];

    public $booking;
    public $rooms = [];
    public $hotels = [];
    public $countries = [];

    public function mount($id)
    {
        $this->booking      = Booking::with(['rooms.room', 'rooms.room.room_type'])->find($id);
        $this->rooms        = $this->booking->rooms;
        $this->countries    = countries();
    }

    public function render()
    {
        return view('livewire.admin.booking.edit-hotel')->layout('layouts.admin');
    }

    public function edit($editing)
    {
        $this->editing = $editing;
    }

    public function submit()
    {
        $this->validate();
        foreach ($this->rooms as $roomData) {
            $roomData->save();
        }
        session()->flash('message', 'Booking updated successfully.');
    }

    public function rules(): array
    {
        return [
            'rooms.*.title' => ['required', 'string'],
            'rooms.*.first_name' => ['required', 'string'],
            'rooms.*.last_name' => ['required', 'string'],
            'rooms.*.date_of_birth' => ['required', 'string'],
            'rooms.*.phone' => ['required', 'string'],
            'rooms.*.nationality' => ['required', 'string']
        ];
    }
}

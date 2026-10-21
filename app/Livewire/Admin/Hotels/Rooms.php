<?php

namespace App\Livewire\Admin\Hotels;

use App\Models\Hotel;
use App\Models\RoomType;
use Livewire\Component;

class Rooms extends Component
{
    public $hotel;
    public $availability = [
        'room_id' => null,
        'start_date' => null,
        'end_date' => null,
        'price' => 0,
    ];
    public $showingInactive = false;
    public $editRoom;
    public $createRoom;
    public $customPricing = [];
    public $new_room = [
        'name' => '',
        'max_capacity' => 0,
        'start_date' => '',
        'end_date' => '',
        'default_price' => 0
    ];
    public $min_date;
    public $max_date;
    public $types;
    public function mount($hotel)
    {
        $this->hotel = Hotel::with('rooms.room_type')->find($hotel);
//        $this->types = RoomType::whereHas('rooms', function ($query) use ($hotel) {
//            $query->where('hotel_id', $hotel);
//        })->withCount('rooms')->get()->unique('id');
    }

    public function render()
    {
        return view('livewire.admin.hotels.rooms')->layout('layouts.admin', ['title' => 'Rooms']);
    }

    public function showInactive()
    {
        $this->showingInactive = !$this->showingInactive;
    }

    public function addRoom()
    {
        $this->new_room['hotel_id'] = $this->hotel->id;
        $this->hotel->rooms()->create($this->new_room);
        $this->new_room = [];
        $this->mount($this->hotel);
        $this->createRoom = false;
    }

    public function showAddRoom()
    {
        $this->createRoom = true;
    }

    public function addPrices($id)
    {
        $this->availability['room_id'] = $id;

    }

    public function savePrices()
    {
        if($this->availability['room_id'] == null) {
            $this->hotel->rooms()->create($this->availability);
        } else {
            $this->hotel->rooms()->find($this->availability['room_id'])->prices()->create($this->availability);
        }
        $this->resetAvailability();
        $this->mount($this->hotel);
        $this->dispatch('close-modals');
    }

    public function resetAvailability()
    {
        $this->availability = [
            'room_id' => null,
            'start_date' => null,
            'end_date' => null,
            'price' => 0,
        ];
    }
}

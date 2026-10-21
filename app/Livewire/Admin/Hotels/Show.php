<?php

namespace App\Livewire\Admin\Hotels;

use App\Models\Contract;
use App\Models\Hotel;
use App\Models\HotelContract;
use App\Models\RoomType;
use App\Models\Season;
use Livewire\Component;

class Show extends Component
{
    public $mode;
    public $hotel;
    public $roomTypes   = [];
    public $seasons     = [];
    public $selectedSeason = null;
    public $data = [];

    public function mount($hotel)
    {
        $this->mode = 'Contracts';
        $hotel = Hotel::findOrFail($hotel);
        $this->hotel = $hotel;
        $this->seasons = Season::where('active', 1)->get();
        $this->initFields();
    }

    public function render()
    {
        if($this->mode == 'Contracts') {
            $contracts = HotelContract::query();
            $contracts->where('hotel_id', $this->hotel->id);
            $contracts->with(['rooms']);
            $this->data = $contracts->get();
        } elseif ($this->mode == 'Rooms') {

        }
        return view('livewire.admin.hotels.show')->layout('layouts.admin');
    }

    public function addSeason()
    {
        dd($this->selectedSeason);
    }

    public function initFields()
    {
        $this->seasons = Season::query()->pluck('name', 'id')->toArray();
        $this->roomTypes = RoomType::query()->pluck('name', 'id')->toArray();
    }
}

<?php

namespace App\Http\Livewire\Admin\Hotels;

use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Season;
use Livewire\Component;

class AddContract extends Component
{
    public $hotel;
    public array $seasons;
    public array $form = [
        'bb_status'     => 0,
        'bb'            => null,
        'hb_status'     => 0,
        'hb'            => null,
        'fb_status'     => 0,
        'fb'            => null,
        'ai_status'     => 0,
        'ai'            => null,
        'sai_status'     => 0,
        'sai'            => null,
        'uai_status'     => 0,
        'uai'            => null,
    ];

    public function mount($hotel)
    {
        $this->hotel = Hotel::find($hotel);
        $this->initFields();
    }

    public function render()
    {
        return view('livewire.admin.hotels.add-contract')->layout('layouts.admin');
    }

    public function save()
    {

    }

    public function initFields()
    {
        $this->seasons = Season::query()->pluck('name', 'id')->toArray();
    }
}

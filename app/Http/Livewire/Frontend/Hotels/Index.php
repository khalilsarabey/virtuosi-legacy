<?php

namespace App\Http\Livewire\Frontend\Hotels;

use App\Models\Hotel;
use App\Models\Room;
use App\Services\Local\HotelService;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $hotels  = [];
    public $query   = [];
    public $results = [];
    public $startDate;
    public $endDate;
    public function mount()
    {
        $this->query = request()->except('_token');
        $query = request();

        if($this->query['type'] == 'hotel') {
            $hotelId        = $query->input('hotel');
            $destination    = $query->input('from');
            $dateRange      = $query->input('date');
            $rooms          = $query->input('rooms');
            [$this->startDate, $this->endDate] = explode(' to ', $dateRange);
            $this->hotels = HotelService::search_hotels($destination, $this->startDate, $this->endDate, $rooms, $hotelId);
        }
    }
    public function render()
    {
        return view('livewire.frontend.hotels.index');
    }
}

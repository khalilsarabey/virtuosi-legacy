<?php

namespace App\Livewire\Frontend\Components\Header\Search;

use App\Models\Hotel;
use Carbon\Carbon;
use Livewire\Component;

class Hotels extends Component
{
    public $destination;
    public $hotel;
    public $hotels = [];
    public $query = [];
    public $destinations = [];
    public $numOfRooms = 1;
    public $adults = 1;
    public $infants = 0;
    public $children = 0;
    public $date;
    public $rooms = [
        ['adult' => 1, 'child' => 0, 'infant' => 0]
    ];

    public function deleteRoom($k)
    {
        unset($this->rooms[$k]);
        $this->rooms = array_values($this->rooms);
        $this->numOfRooms -= 1;
    }

    public function addRoom()
    {
        $this->rooms[] = [
            'adult'     => 1,
            'child'     => 0,
            'infant'    => 0,
        ];
        $this->adults += 1;
    }

    public function removeRoom($key)
    {
        unset($this->rooms[$key]);
        $this->rooms = array_values($this->rooms);
    }


    public function updatedNumOfRooms()
    {
        if($this->numOfRooms > count($this->rooms)) {
            $num = $this->numOfRooms - count($this->rooms);
            for($i = 0; $i < $num; $i++) {
                $this->rooms[] = ['adult' => 1, 'child' => 0, 'infant' => 0];
            }
        } else {
            $num = count($this->rooms) - $this->numOfRooms;
            for($i = 0; $i < $num; $i++) {
                array_pop($this->rooms);
            }
        }
    }

    public function mount()
    {
        $this->query = request()->query();
        $currentDate = Carbon::now();
        $this->destinations = Hotel::whereHas('rooms', function ($query) use ($currentDate) {
            $query->whereDate('start_date', '<=', $currentDate)
                ->orWhereDate('end_date', '>=', $currentDate);
        })->with('city')->select('location')->groupBy('location')->get()
            ->mapWithKeys(fn ($hotel) => [$hotel->location => $hotel->city?->city ?? $hotel->location])->all();
        $this->populate();
    }

    public function updatedDestination($v, $k = null)
    {
        $this->hotels = Hotel::where('location', $v)->where('active', 1)->pluck('name', 'id')->toArray();
        $this->dispatch('set-hotel-date');
    }

    public function render()
    {
        return view('livewire.frontend.components.header.search.hotels');
    }

    public function populate()
    {
        if(count($this->query) && $this->query['type'] == 'hotel') {
            $this->destination  = $this->query['from'];
            $this->date         = $this->query['date'];
            $this->hotel        = $this->query['hotel'];
            $this->rooms = [];
            $adults     = 0;
            $children   = 0;
            $infants    = 0;
            foreach($this->query['rooms'] as $room) {
                $this->rooms[] = [
                    'adult'     => $room['adult'],
                    'child'     => $room['child'],
                    'infant'    => $room['infant']
                ];
                $adults   += $room['adult'];
                $children += $room['child'];
                $infants  += $room['infant'];
            }
            if(count($this->query['rooms'])) {
                $this->adults     = $adults;
                $this->children   = $children;
                $this->infants    = $infants;
            }
            $this->hotels = Hotel::where('location', $this->destination)->where('active', 1)->pluck('name', 'id')->toArray();
        }
    }

    public function inc($passenger, $key)
    {
        $this->rooms[$key][$passenger]++;
        $person = $this->convertPassengerTitles($passenger);
        $this->{$person}++;
    }

    public function dec($passenger, $key)
    {
        if($this->rooms[$key][$passenger] > 0) {
            $this->rooms[$key][$passenger]--;
            $person = $this->convertPassengerTitles($passenger);
            $this->{$person}--;
        }
    }

    public function convertPassengerTitles($passenger): ?string
    {
        return match ($passenger) {
            'adult' => 'adults',
            'child' => 'children',
            'infant' => 'infants',
            default => ''
        };
    }

}

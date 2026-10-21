<?php

namespace App\Livewire\Frontend\Components\Header\Search;

use App\Services\FlightService;
use Carbon\Carbon;
use Livewire\Component;

class Packages extends Component
{

    public $locations       = [];
    public $query           = [];
    public $fromList        = [];
    public $toList          = [];
    public $departureList   = [];
    public $returnList      = [];

    public $dep_from_code   = '';
    public $dep_to_code     = '';
    public $dep_date        = '';
    public $ret_date        = '';
    public $adults          = 1;
    public $infants         = 0;
    public $children        = 0;
    public $max_capacity    = null;
    public $rooms           = [
        [
            'adult'     => 1,
            'child'     => 0,
            'infant'    => 0,
        ]
    ];

    public function mount(): void
    {
        $this->query = request()->query();
        $locations = getSearchData();
        $locations = $locations
            ->where('type', 'oneway')
            ->unique('dep_from_city_code')
            ->pluck('dep_from_city_code', 'dep_from_city')
            ->toArray();
        $this->fromList = $locations;
        $this->populate();
    }

    public function render()
    {
        return view('livewire.frontend.components.header.search.packages');
    }

    public function populate(): void
    {
        if(request()->input('type') == 'roundtrip' && request()->input('is_package', 0) == 1) {
            $this->dep_from_code = request()->input('dep_from_code');
            $this->updatedDepFromCode();
            $this->dep_to_code   = $this->query['dep_to_code'];
            $this->updatedDepToCode();
            $this->dep_date     = $this->query['dep_date'];
            $this->updatedDepDate();
            $this->ret_date     = $this->query['ret_date'];
            $this->updatedReturn();
            $this->rooms        = [];
            $adults             = 0;
            $children           = 0;
            $infants            = 0;
            if(isset($this->query['rooms'])) {
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
                $this->adults     = $adults;
                $this->children   = $children;
                $this->infants    = $infants;
            }
        }
    }

    public function addRoom(): void
    {
        if($this->validatePassengersNumber()) {
            $this->rooms[] = [
                'adult'     => 1,
                'child'     => 0,
                'infant'    => 0,
            ];
            $this->adults += 1;
        }
    }

    public function removeRoom($key): void
    {
        unset($this->rooms[$key]);
        $this->rooms = array_values($this->rooms);
    }

    public function updatedDepFromCode(): void
    {
        $this->dep_to_code  = '';
        $this->dep_date     = '';
        $this->ret_date     = '';
        $this->adults       = 1;
        $this->children     = 0;
        $this->infants      = 0;
        $this->dispatch('set-package-passengers-number', passengers: $this->adults + $this->infants + $this->children);
        $locations = getSearchData();
        $locations = $locations
            ->where('dep_from_city_code', $this->dep_from_code)
            ->where('type', 'oneway')
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
            ->unique('dep_to_city_code')
            ->pluck('dep_to_city_code', 'dep_to_city')
            ->toArray();
        $this->toList = $locations;
    }

    public function updatedDepToCode(): void
    {
        $this->dep_date = '';
        $this->ret_date = '';
        $this->adults = 1;
        $this->children = 0;
        $this->infants = 0;
        $this->dispatch('set-package-passengers-number', passengers: $this->adults + $this->infants + $this->children);
        $locations = getSearchData();
        $locations = $locations
            ->where('dep_from_city_code', $this->dep_from_code)
            ->where('dep_to_city_code', $this->dep_to_code)
            ->where('type', 'oneway')
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
            ->filter(function ($location) {
                $date = Carbon::today()->addDays(isset($location['stop_booking_before']) && $location['stop_booking_before'] > 0 ? $location['stop_booking_before'] : 0);
                return Carbon::parse($location['dep_date'])->greaterThanOrEqualTo($date);
            })
            ->unique('dep_date')
            ->pluck('dep_date')
            ->sortBy(function($date) {
                return Carbon::createFromFormat('d-m-Y', $date);
            })
            ->values()
            ->all();
        $this->departureList = $locations;
        $this->dispatch('set-package-departure-date', dates: $this->departureList);
    }

    public function updatedDepDate(): void
    {
        //Reset other filters
        $this->ret_date = '';
        $this->adults   = 1;
        $this->children = 0;
        $this->infants  = 0;
        $this->dispatch('set-package-passengers-number', passengers: $this->adults + $this->infants + $this->children);

        $targetDate = Carbon::parse($this->dep_date);

        $locations = getSearchData();
        $locations = $locations
            ->where('dep_from_city_code', $this->dep_to_code)
            ->where('dep_to_city_code', $this->dep_from_code)
            ->where('type', 'oneway')
            ->filter(function ($location) {
                if(auth()->user()->agency_id == 1) {
                    return true;
                } else {
                    return isset($location['is_connection']) && $location['is_connection'] === false;
                }
            })
            ->filter(function ($location) use ($targetDate) {
                return Carbon::parse($location['dep_date'])->gt($targetDate);
            })
            ->unique('dep_date')
            ->pluck('dep_date')
            ->toArray();
        $this->returnList = $locations;
        $this->dispatch('set-package-return-date', dates: $this->returnList);
    }

    public function updatedReturn(): void
    {
        $this->adults = 1;
        $this->children = 0;
        $this->infants = 0;
        $this->dispatch('set-package-passengers-number', passengers: $this->adults + $this->infants + $this->children);
    }

    public function inc($passenger, $key): void
    {
        if($this->validatePassengersNumber() || $passenger == 'infant')
        {
            $this->rooms[$key][$passenger]++;
            $person = $this->convertPassengerTitles($passenger);
            $this->{$person}++;
        }
    }

    public function dec($passenger, $key): void
    {
        if($this->rooms[$key][$passenger] > 0) {
            $this->rooms[$key][$passenger]--;
            $person = $this->convertPassengerTitles($passenger);
            $this->{$person}--;
        }
    }

    public function validatePassengersNumber(): bool
    {
        $adults     = 0;
        $children   = 0;
        foreach($this->rooms as $room)
        {
            $adults   += $room['adult'];
            $children += $room['child'];
        }
        return $adults + $children < 9;
    }

    public function deleteRoom($k): void
    {
        unset($this->rooms[$k]);
        $this->rooms = array_values($this->rooms);
        $this->numOfRooms -= 1;
    }

    public function updatedNumOfRooms(): void
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

<?php

namespace App\Livewire\Frontend\Components\Header\Search\LowCost;

use App\Models\Location;
use Livewire\Component;

class Search extends Component
{
    public $type;
    public $query = [];
    public $oneway = [];
    public $airports;

    public function mount()
    {
        $this->type = 'oneway';
        $this->oneway = [
            'from' => '',
            'to' => '',
            'departure' => '',
            'adults' => 1,
            'infants' => 0,
            'children' => 0
        ];
        $this->airports = Location::where('active', 1)->get();
    }

    public function render()
    {
        return view('livewire.frontend.components.header.search.low-cost.search');
    }

    public function inc($type, $passenger)
    {
        $this->{$type}->{$passenger}++;
    }

    public function dec($type, $passenger)
    {
        if($this->{$type}->{$passenger} > 0) {
            $this->{$type}->{$passenger}--;
        }
    }

    public function updatedOnewayTo()
    {
        $this->dispatch('set-oneway-departure-date');
    }
}

<?php

namespace App\Livewire\Frontend\Components\Header\Search\LowCost;

use App\Models\Location;
use App\Services\Airlines\AirlineSearchService;
use App\Support\AirlineSupport;
use Livewire\Component;

class Container extends Component
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
        $this->airports = Location::where('active', 1)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.components.header.search.low-cost.container');
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

    public function search()
    {
        if($this->type == 'oneway')
        {
            $object = new \stdClass();
            $object->flexible_dates     = false;
            $object->dep_from           = $this->oneway['from'];
            $object->dep_to             = $this->oneway['to'];
            $object->dep_date           = $this->oneway['departure'];
            $object->adult              = $this->oneway['adults'];
            $object->child              = $this->oneway['children'];
            $object->infant             = $this->oneway['infants'];
            $data = (new AirlineSupport())->processArray($object);
            $results = (new AirlineSearchService())->process($data);
        }

        if($this->type == 'roundtrip') {

        }
    }
}

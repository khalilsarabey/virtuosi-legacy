<?php

namespace App\Livewire\Frontend\Components\Header\Search;

use Livewire\Component;

class LowcostOneway extends Component
{
    public $departure;
    public $return;
    public $from;
    public $to;
    public $infant = 0;
    public $child = 0;
    public $adult = 1;

    public function render()
    {
        return view('livewire.frontend.components.header.search.lowcost-oneway');
    }


    public function inc($passenger): void
    {
        if(($this->adult + $this->child < 9) || $passenger == 'infants') {
            $this->{$passenger}++;
        } else {
            $this->{$passenger}++;
        }
    }

    public function dec($passenger): void
    {
        if($this->{$passenger} > 0) {
            $this->{$passenger}--;
        }
    }
}

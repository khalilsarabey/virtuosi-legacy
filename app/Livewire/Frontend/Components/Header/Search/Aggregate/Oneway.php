<?php

namespace App\Livewire\Frontend\Components\Header\Search\Aggregate;

use Livewire\Component;

class Oneway extends Component
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
        return view('livewire.frontend.components.header.search.aggregate.oneway');
    }
}

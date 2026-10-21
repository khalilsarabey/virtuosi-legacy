<?php

namespace App\Http\Livewire\Frontend\Components\Header\Search;

use Livewire\Component;

class Container extends Component
{
    public $query = [];
    public $type;
    public $sub = 0;
    public $tab;
    public $locations;

    public function mount()
    {
        $this->query = request()->query();

        if(count($this->query) && isset($this->query['type'])) {
            $this->type = in_array($this->query['type'], ['oneway', 'fixed', 'multi', 'roundtrip', 'hotel', 'package']) ? $this->query['type'] : 'roundtrip';
        }
        $locations = getSearchData();
        $this->locations = $locations->unique('dep_from_code')->pluck('dep_from_city_code', 'dep_from_city')->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.components.header.search.container');
    }

    public function tab($tab)
    {
        $this->type = $tab;
    }
}

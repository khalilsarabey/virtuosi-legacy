<?php

namespace App\Livewire\Admin\Settings\Agencies;

use App\Models\PricingClass;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $agencies;
    public $selected = [];
    public $classes = [];
    public $class;

    public function mount()
    {
        $this->classes = PricingClass::orderBy('name')->get();
        $this->agencies = User::where('type', 'agency')->orderBy('name')->with('custom_pricings.airline', 'pricing_class')->get();
    }

    public function render()
    {
        return view('livewire.admin.settings.agencies.index')->layout('layouts.admin');
    }

    public function assign()
    {
        if(count($this->selected) && $this->class != ''):
            User::whereIn('id', $this->selected)->update(['pricing_class_id' => $this->class]);
            $this->selected = [];
            $this->mount();
        endif;
    }

    public function unassign()
    {
        if(count($this->selected)):
            User::whereIn('id', $this->selected)->update(['pricing_class_id' => null]);
            $this->selected = [];
            $this->mount();
        endif;
    }
}

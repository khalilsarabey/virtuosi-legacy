<?php

namespace App\Http\Livewire\Admin\Settings\PricingClasses;

use App\Models\PricingClass;
use Livewire\Component;

class Index extends Component
{
    public $classes;

    public function mount()
    {
        $this->classes = PricingClass::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.admin.settings.pricing-classes.index')->layout('layouts.admin');
    }

    public function delete($id)
    {
        PricingClass::find($id)->delete();
        $this->mount();
    }
}

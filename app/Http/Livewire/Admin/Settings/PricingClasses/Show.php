<?php

namespace App\Http\Livewire\Admin\Settings\PricingClasses;

use App\Models\PricingClass;
use Livewire\Component;

class Show extends Component
{
    public $class;
    public function mount($id)
    {
        $this->class = PricingClass::with('agencies.custom_pricings')->find($id);
    }

    public function render()
    {
        return view('livewire.admin.settings.pricing-classes.show')->layout('layouts.admin');
    }

    public function remove($id)
    {
        $user = \App\Models\User::where('id', $id)->first();
        $user->pricing_class_id = null;
        $user->save();
        $this->mount($this->class->id);
    }
}

<?php

namespace App\Livewire\Admin\Settings\PricingClasses;

use App\Models\PricingAirline;
use App\Models\PricingClass;
use Livewire\Component;

class Create extends Component
{
    public $class;

    public function mount()
    {
        $this->class['airlines']    = [];
        $this->class['name']        = '';
        $airlines = PricingAirline::orderBy('name')->get();
        foreach($airlines as $airline):
            $this->class['airlines'][] = [
                'pricing_airline_id'    => $airline->id,
                'name'                  => $airline->name,
                'selling_commission'    => $airline->default_selling_commission,
                'selling_service_fee'   => $airline->default_selling_service_fee,
                'selling_fixed_fee'     => $airline->default_selling_fixed_fee,
                'net_commission'        => $airline->default_net_commission,
                'net_service_fee'       => $airline->default_net_service_fee
            ];
        endforeach;
    }

    public function render()
    {
        return view('livewire.admin.settings.pricing-classes.create')->layout('layouts.admin');
    }

    public function save()
    {
        $pricingClass = PricingClass::create(['name' => $this->class['name']]);
        $pricingClass->details()->createMany($this->class['airlines']);
        return redirect()->route('admin.settings.auto-pricing.classes.index');
    }
}

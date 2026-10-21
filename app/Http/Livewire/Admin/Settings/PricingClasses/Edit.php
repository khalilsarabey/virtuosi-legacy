<?php

namespace App\Http\Livewire\Admin\Settings\PricingClasses;

use App\Models\PricingAirline;
use App\Models\PricingClass;
use Livewire\Component;

class Edit extends Component
{
    public $class;

    public function mount($id)
    {
        $class = PricingClass::with('details')->find($id);
        $airlines = PricingAirline::orderBy('name')->get();
        $this->class = [
            'name' => $class->name,
            'id' => $class->id,
            'airlines' => [],
        ];
        foreach ($airlines as $airline) {
            $detail = $class->details->where('pricing_airline_id', $airline->id)->first();
            $this->class['airlines'][] = [
                'pricing_airline_id'    => optional($detail)->pricing_airline_id ?? $airline->id,
                'pricing_class_id'      => $class->id,
                'name'                  => $airline->name,
                'selling_commission'    => optional($detail)->selling_commission ?? $airline->default_selling_commission,
                'selling_service_fee'   => optional($detail)->selling_service_fee ?? $airline->default_selling_service_fee,
                'selling_fixed_fee'   => optional($detail)->selling_fixed_fee ?? $airline->default_selling_fixed_fee,
                'net_commission'        => optional($detail)->net_commission ?? $airline->default_net_commission,
                'net_service_fee'       => optional($detail)->net_service_fee ?? $airline->default_net_service_fee,
            ];
        }
    }


    public function render()
    {
        return view('livewire.admin.settings.pricing-classes.edit')->layout('layouts.admin');
    }

    public function save()
    {
        try {
            $price = PricingClass::where('id', $this->class['id'])->first();
            $price->update(['name' => $this->class['name']]);
            $price->details()->delete();
            $price->details()->createMany($this->class['airlines']);
            return redirect()->route('admin.settings.auto-pricing.classes.index');
        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }
}

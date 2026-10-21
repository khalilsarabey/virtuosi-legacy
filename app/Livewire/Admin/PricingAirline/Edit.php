<?php

namespace App\Livewire\Admin\PricingAirline;

use App\Models\PricingAirline;
use Livewire\Component;

class Edit extends Component
{
    public PricingAirline $airline;

    public function mount($id)
    {
        $this->airline          = PricingAirline::find($id);
    }

    public function render()
    {
        return view('livewire.admin.pricing-airline.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();

        $this->airline->save();

        return redirect()->route('admin.settings.auto-pricing.airlines.index');
    }

    protected function rules(): array
    {
        return [
            'airline.name' => [
                'string',
                'required',
            ],
            'airline.tour_code' => [
                'string',
                'nullable',
            ],
            'airline.agency_remark' => [
                'string',
                'nullable',
            ],
            'airline.fare_remark' => [
                'string',
                'nullable',
            ],
            'airline.default_selling_commission' => [
                'required',
            ],
            'airline.default_selling_service_fee' => [
                'required',
            ],
            'airline.default_selling_fixed_fee' => [
                'required',
            ],
            'airline.default_net_commission' => [
                'required',
            ],
            'airline.default_net_service_fee' => [
                'required',
            ]
        ];
    }
}

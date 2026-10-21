<?php

namespace App\Http\Livewire\Admin\Pricing;

use App\Models\Pricing;
use App\Models\PricingAirline;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $selectedAgency;
    public $selectedAirline;
    public $pricings;
    public $agencies;
    public $airlines;
    public $airline;

    protected $queryString = [
        'selectedAirline',
        'selectedAgency'
    ];

    public function mount()
    {
        $this->pricings = [];
        $this->agencies = User::where('type', 'agency')->pluck('name', 'id')->toArray();
        $this->airlines = PricingAirline::pluck('name', 'id')->toArray();
        $this->populate();
    }

    public function render()
    {
        return view('livewire.admin.pricing.index')->layout('layouts.admin');
    }

    public function updatedSelectedAgency()
    {
        $this->populate();
    }

    public function updatedSelectedAirline()
    {
        $this->populate();
    }

    public function populate()
    {
        $this->pricings = [];
        if($this->selectedAgency && $this->selectedAirline) {
            $pricing = Pricing::where([
                'user_id' => $this->selectedAgency,
                'pricing_airline_id' => $this->selectedAirline
            ])->first();
            if($pricing) {
                $this->pricings = [
                    'pricing_id'            => $pricing->id,
                    'selling_commission'    => $pricing->selling_commission,
                    'selling_service_fee'   => $pricing->selling_service_fee,
                    'selling_fixed_fee'     => $pricing->selling_fixed_fee,
                    'net_commission'        => $pricing->net_commission,
                    'net_service_fee'       => $pricing->net_service_fee,
                    'agency_remark'         => $pricing->agency_remark,
                    'fare_remark'           => $pricing->fare_remark,
                ];
            } else {
                $airline = PricingAirline::where('id', $this->selectedAirline)->first();
                $this->pricings = [
                    'airline_id'            => $airline->id,
                    'selling_commission'    => $airline->default_selling_commission,
                    'selling_service_fee'   => $airline->default_selling_service_fee,
                    'selling_fixed_fee'   => $airline->default_selling_fixed_fee,
                    'net_commission'        => $airline->default_net_commission,
                    'net_service_fee'       => $airline->default_net_service_fee,
                    'agency_remark'         => $airline->agency_remark,
                    'fare_remark'           => $airline->fare_remark,
                ];
            }
        }
    }

    public function save()
    {
        if(isset($this->pricings['pricing_id'])) {
            $pricing = Pricing::where([
                'user_id' => $this->selectedAgency,
                'pricing_airline_id' => $this->selectedAirline
            ])->first();
            $pricing->update($this->pricings);
            $this->populate();
        } elseif(isset($this->pricings['airline_id'])) {
            $pricing = new Pricing;
            $pricing->user_id = $this->selectedAgency;
            $pricing->pricing_airline_id = $this->selectedAirline;
            $pricing->selling_commission = $this->pricings['selling_commission'];
            $pricing->selling_service_fee = $this->pricings['selling_service_fee'];
            $pricing->selling_fixed_fee = $this->pricings['selling_fixed_fee'];
            $pricing->net_commission = $this->pricings['net_commission'];
            $pricing->net_service_fee = $this->pricings['net_service_fee'];
            $pricing->agency_remark = $this->pricings['agency_remark'];
            $pricing->fare_remark = $this->pricings['fare_remark'];
            $pricing->save();
            $this->populate();
        }
    }

    public function delete($id)
    {
        $pricing = Pricing::where('id', $id)->first();
        if($pricing) {
            try {
                $pricing->delete();
            } catch(\Exception $e) {

            }
            $this->populate();
        }
    }
}

<?php

namespace App\Http\Livewire\Admin\Turkish\Custom;

use App\Http\Livewire\WithConfirmation;
use App\Models\PricingAirline;
use App\Models\TurkishCustomPricing;
use App\Models\TurkishCustomPricingDetail;
use App\Models\TurkishDestination;
use App\Models\TurkishDestinationDetail;
use App\Models\TurkishSection;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    use WithConfirmation;
    public $agency_remark;
    public $fare_remark;
    public $class_name;
    public $selling_commission;
    public $selling_service_fee;
    public $selling_fixed_fee;
    public $net_commission;
    public $net_service_fee;
    public $customData = [
        [
            'class_name' => '',
            'selling_commission' => 0.00,
            'elling_service_fee' => 0.00,
            'selling_fixed_fee' => 0.00,
            'net_commission' => 0.00,
            'net_service_fee' => 0.00,
        ]
    ];
    public $selectedAgency;
    public $selectedDestination;
    public $agencies;
    public $destinations;
    public $customDestination;
    public $section;
    /**
     * @var true
     */
    public bool $addingClass;
    public $class;

    public function mount(TurkishSection $section)
    {
        $this->section = $section;
        $this->agencies = User::where('type', 'agency')->pluck('name', 'id')->toArray();
        $this->destinations = TurkishDestination::where('turkish_section_id', $this->section->id)->pluck('name', 'id')->toArray();
    }
    public function render()
    {
        $this->populate();
        return view('livewire.admin.turkish.custom.index')->layout('layouts.admin');
    }

    public function import()
    {
        $destination = TurkishDestination::with('classes')->find($this->selectedDestination);
        $data = [];
        foreach($destination->classes as $class):
            $data[] = [
                'name' => $class->name,
                'selling_commission' => $class->selling_commission,
                'selling_service_fee' => $class->selling_service_fee,
                'selling_fixed_fee' => $class->selling_fixed_fee,
                'net_commission' => $class->net_commission,
                'net_service_fee' => $class->net_service_fee,
            ];
        endforeach;
        $this->customDestination->classes()->delete();
        $this->customDestination->classes()->createMany($data);
        $this->resetClass();
    }

    public function updatedSelectedAgency()
    {
        $this->populate();
    }

    public function updatedSelectedDestination()
    {
        $this->populate();
    }

    public function populate()
    {
        if($this->selectedAgency && $this->selectedDestination)
        {
            $this->customDestination = TurkishCustomPricing::where([
                'user_id' => $this->selectedAgency,
                'turkish_destination_id' => $this->selectedDestination
            ])
                ->with('classes')
                ->first();
            if($this->customDestination) {
                $this->agency_remark = $this->customDestination->agency_remark;
                $this->fare_remark = $this->customDestination->fare_remark;
            } else {
                $this->agency_remark = '';
                $this->fare_remark = '';
            }
        }
    }

    public function addMore()
    {
        $this->customData[] = [
            'class_name' => '',
            'selling_commission' => 0.00,
            'elling_service_fee' => 0.00,
            'selling_fixed_fee' => 0.00,
            'net_commission' => 0.00,
            'net_service_fee' => 0.00,
        ];
    }

    public function create()
    {
        TurkishCustomPricing::create([
            'user_id' => $this->selectedAgency,
            'turkish_destination_id' => $this->selectedDestination,
            'agency_remark' => $this->agency_remark,
            'fare_remark' => $this->fare_remark
        ]);
    }

    public function update()
    {
        $this->customDestination->update([
            'agency_remark' => $this->agency_remark,
            'fare_remark' => $this->fare_remark
        ]);
    }

    public function saveClass()
    {
        $this->validate([
            'class_name' => 'required',
            'selling_commission' => 'required',
            'selling_service_fee' => 'required',
            'selling_fixed_fee' => 'required',
            'net_commission' => 'required',
            'net_service_fee' => 'required',
        ]);
        $this->customDestination->classes()->create([
            'name' => $this->class_name,
            'agency_remark' => $this->agency_remark,
            'fare_remark' => $this->fare_remark,
            'selling_commission' => $this->selling_commission,
            'selling_service_fee' => $this->selling_service_fee,
            'selling_fixed_fee' => $this->selling_fixed_fee,
            'net_commission' => $this->net_commission,
            'net_service_fee' => $this->net_service_fee,
        ]);
        $this->resetClass();
    }

    public function editClass($id)
    {
        $this->class = TurkishCustomPricingDetail::find($id);
        $this->class_name = $this->class->name;
        $this->selling_commission = $this->class->selling_commission;
        $this->selling_service_fee = $this->class->selling_service_fee;
        $this->selling_fixed_fee = $this->class->selling_fixed_fee;
        $this->net_commission = $this->class->net_commission;
        $this->net_service_fee = $this->class->net_service_fee;
    }

    public function updateClass()
    {
        $this->class->update([
            'name' => $this->class_name,
            'agency_remark' => $this->agency_remark,
            'fare_remark' => $this->fare_remark,
            'selling_commission' => $this->selling_commission,
            'selling_service_fee' => $this->selling_service_fee,
            'selling_fixed_fee' => $this->selling_fixed_fee,
            'net_commission' => $this->net_commission,
            'net_service_fee' => $this->net_service_fee,
        ]);
        $this->resetClass();
    }

    public function resetClass()
    {
        $this->class = null;
        $this->class_name = null;
        $this->agency_remark = null;
        $this->fare_remark = null;
        $this->selling_commission = null;
        $this->selling_service_fee = null;
        $this->selling_fixed_fee = null;
        $this->net_commission = null;
        $this->net_service_fee = null;
    }

    public function deleteClass($id)
    {
        $class = TurkishCustomPricingDetail::find($id);
        $class->delete();
    }

    public function deleteCustom()
    {
        $this->customDestination->delete();
    }
}

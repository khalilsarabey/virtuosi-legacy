<?php

namespace App\Livewire\Admin\Turkish\Destinations;

use App\Livewire\WithConfirmation;
use App\Models\TurkishDestination;
use App\Models\TurkishDestinationDetail;
use App\Models\TurkishSection;
use Livewire\Component;

class Index extends Component
{
    use WithConfirmation;
    public $destinations;
    public $destination_name;
    public $agency_remark;
    public $fare_remark;
    public $class_name;
    public $selling_commission;
    public $selling_service_fee;
    public $selling_fixed_fee;
    public $net_commission;
    public $net_service_fee;
    public $destination;
    public $class;
    public $section;

    public function mount(TurkishSection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        $this->destinations = TurkishDestination::with('classes')->where('turkish_section_id', $this->section->id)->orderBy('name')->get();
        return view('livewire.admin.turkish.destinations.index')->layout('layouts.admin');
    }

    public function addClass($id)
    {
        $this->destination = TurkishDestination::find($id);
        $this->resetClass();
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
        $this->destination->classes()->create([
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
        $this->class = TurkishDestinationDetail::find($id);
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

    public function addDestination()
    {
        $destination = new TurkishDestination();
        $destination->turkish_section_id = $this->section->id;
        $destination->name = $this->destination_name;
        $destination->agency_remark = $this->agency_remark;
        $destination->fare_remark = $this->fare_remark;
        $destination->save();
    }

    public function deleteDestination($id)
    {
        $destination = TurkishDestination::find($id);
        $destination->delete();
    }

    public function deleteClass($id)
    {
        $class = TurkishDestinationDetail::find($id);
        $class->delete();
    }

    public function editDestination($id)
    {
        $this->destination = TurkishDestination::find($id);
        $this->destination_name = $this->destination->name;
        $this->agency_remark = $this->destination->agency_remark;
        $this->fare_remark = $this->destination->fare_remark;
    }

    public function updateDestination()
    {
        $this->destination->update([
            'name' => $this->destination_name,
            'agency_remark' => $this->agency_remark,
            'fare_remark' => $this->fare_remark,
        ]);
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
}

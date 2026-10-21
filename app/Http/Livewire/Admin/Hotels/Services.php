<?php

namespace App\Http\Livewire\Admin\Hotels;

use App\Models\RoomService;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class Services extends Component
{
    public $showForm = false;
    public $serviceId = null;
    public $serviceName;

    public function render()
    {
        $services = RoomService::query()->get();
        return view('livewire.admin.hotels.services', compact('services'))
            ->layout('layouts.admin');
    }

    public function addService()
    {
        $this->serviceName = '';
        $this->showForm = true;
        $this->serviceId = null;
    }

    public function editService($id)
    {
        $this->serviceId = $id;
        $service = RoomService::find($id);
        $this->serviceName = $service->name;
        $this->showForm = true;
    }

    public function save()
    {
        if(!empty($this->serviceName)) {
            if ($this->serviceId) {
                RoomService::find($this->serviceId)->update([
                    'name' => $this->serviceName,
                ]);
            } else {
                RoomService::create([
                    'name' => $this->serviceName,
                ]);
            }
            $this->showForm = false;
        }
    }

    public function deleteService($id)
    {
        RoomService::destroy($id);
    }
}

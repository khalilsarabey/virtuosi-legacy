<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Agency;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Footer extends Component
{
    public Agency $agency;
    public $footer_contacts;

    public function mount()
    {
        $this->agency = Agency::where('id', auth()->user()->agency_id)->first();
    }

    public function render()
    {
        return view('livewire.admin.settings.footer')->layout('layouts.admin');
    }

    public function save()
    {
        $this->validate();
        try {
            $this->agency->save();
            Cache::forget('settings');
        } catch(\Exception $e) {
            die($e->getMessage());
        }
        $this->mount();
    }
    protected function rules(): array {
        return [
            'agency.contact_email' => ['nullable'],
            'agency.contact_phone' => ['nullable'],
            'agency.contact_address' => ['nullable'],
            'agency.facebook' => ['nullable'],
            'agency.twitter' => ['nullable'],
            'agency.instagram' => ['nullable'],
            'agency.linkedin' => ['nullable'],
            'agency.footer_quote' => ['nullable']
        ];
    }
}

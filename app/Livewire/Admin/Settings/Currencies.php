<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Agency;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Currencies extends Component
{
    public Agency $agency;
    public string $message;
    public string $error;

    public function render()
    {
        $this->agency = Agency::where('id', auth()->user()->agency_id)->first();
        return view('livewire.admin.settings.currencies')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        $this->agency->save();
        Cache::forget('settings');
        $this->message = 'Currency successfully updated.';
    }

    protected function rules()
    {
        return [
            'agency.euro' => 'required',
        ];
    }
}

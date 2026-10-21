<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Models\Agency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Email extends Component
{
    public Agency $agency;
    public string $message;
    public string $error;
    public string $testEmail;

    public function mount()
    {
        $this->message = '';
        $this->testEmail = '';
        $this->error = '';
    }

    public function render()
    {
        $this->agency = Agency::where('id', auth()->user()->agency_id)->first();
        return view('livewire.admin.settings.email')->layout('layouts.admin');
    }

    public function test()
    {
        if(empty($this->testEmail)) {
            $this->error = 'Please enter an email to receive the test.';
            return false;
        }
        try {
            Mail::raw('Hello World! Your E-mail is working!', function($msg) {$msg->to($this->testEmail)->subject('Test Email'); });
            $this->message = "A test email has been sent.";
            $this->error = '';
        } catch(\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->agency->save();
            Cache::forget('settings');
        } catch(\Exception $e) {
            $this->error = $e->getMessage();
        }

    }

    public function rules() : array
    {
        return [
            'agency.mail_host' => 'required',
            'agency.mail_port' => 'required',
            'agency.mail_sender_name' => 'required',
            'agency.mail_sender_email' => 'required|email',
            'agency.mail_username' => 'required',
            'agency.mail_password' => 'required',
            'agency.mail_encryption' => 'required',
        ];
    }
}

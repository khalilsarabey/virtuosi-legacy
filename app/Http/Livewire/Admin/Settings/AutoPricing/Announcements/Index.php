<?php

namespace App\Http\Livewire\Admin\Settings\AutoPricing\Announcements;

use App\Models\PricingAnnouncement;
use Livewire\Component;

class Index extends Component
{
    public $editing = false;
    public $creating = false;
    public $internal = true;
    public $agencies = false;
    public $announcements = [];
    public PricingAnnouncement $announcement;
    public $title = '';
    public $content = '';

    public function mount()
    {
        $this->announcement = new PricingAnnouncement;
    }

    public function render()
    {
        $this->announcements = PricingAnnouncement::orderBy('created_at', 'desc')->get();
        return view('livewire.admin.settings.auto-pricing.announcements.index')->layout('layouts.admin');
    }

    protected $rules = [
        'announcement.title' => 'required',
        'announcement.content' => 'required',
        'announcement.agencies' => 'nullable',
        'announcement.internal' => 'nullable',
    ];

    public function create()
    {
        $this->creating = true;
    }

    public function save()
    {
        $this->announcement = new PricingAnnouncement;
        $this->announcement->title      = $this->title;
        $this->announcement->content    = $this->content;
        $this->announcement->agencies   = $this->agencies;
        $this->announcement->internal   = $this->internal;
        $this->validate();
        $this->announcement->save();
        $this->close();
    }

    public function edit($id)
    {
        $this->editing = true;
        $this->announcement = PricingAnnouncement::find($id);
        $this->title = $this->announcement->title;
        $this->content = $this->announcement->content;
        $this->agencies = $this->announcement->agencies;
        $this->internal = $this->announcement->internal;
    }

    public function update()
    {
        $this->announcement->title      = $this->title;
        $this->announcement->content    = $this->content;
        $this->announcement->agencies   = $this->agencies;
        $this->announcement->internal   = $this->internal;
        $this->announcement->save();
        $this->close();
    }

    public function close()
    {
        $this->editing = false;
        $this->creating = false;
        $this->announcement->title = '';
        $this->announcement->content = '';
        $this->title = '';
        $this->content = '';
    }

    public function delete($id)
    {
        $announcement = PricingAnnouncement::find($id);
        $announcement->delete();
    }
}

<?php

namespace App\Livewire\Admin\WhiteLabel;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Create extends Component
{
    use WithFileUploads;

    public Agency $agency;
    public User $user;
    public string $password = '';

    public function mount()
    {
        $this->agency   = new Agency;
        $this->agency->primary_color            = '#000000';
        $this->agency->secondary_color1         = '#d3ecf3';
        $this->agency->color_success            = '#198754';
        $this->agency->color_info               = '#17a2b8';
        $this->agency->color_warning            = '#f4d449';
        $this->agency->color_danger             = '#c72323';
        $this->agency->color_light              = '#ebebeb';
        $this->agency->color_footer_bg          = '#a4e2ea';
        $this->agency->color_footer_bg_light    = '#c9ecf2';
        $this->agency->color_footer_text        = '#000000';
        $this->agency->color_footer_icons_bg    = '#d3f3f1';
        $this->agency->color_footer_icons_color = '#000000';
        $this->agency->color_body_bg            = '#f0f0f0';
        $this->agency->color_top_menu           = '#000000';

        $this->user     = new User;
    }

    public function render()
    {
        return view('livewire.admin.whitelabel.create')->layout('layouts.admin');
    }

    public function save()
    {
        $this->validate();
        try {
            $this->user->password           = $this->password;
            $this->user->email_verified_at   = now();
            $this->user->type               = 'white-admin';
            $this->user->is_approved        = 1;
            $this->user->active             = 1;
            $this->user->api                = 0;
            $this->user->agency_id          = $this->agency->getNextId();
            $this->user->save();

            $this->agency->user_id = $this->user->id;
            $this->agency->status = 1;
            $this->agency->save();
            Cache::forget('settings');
            session()->flash('message', 'White Label successfully added.');
        } catch(\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
        return redirect()->route('admin.whitelabel.index');
    }

    protected $rules = [
        'agency.name'                       => 'required',
        'agency.email'                      => 'required|email|unique:agencies,email',
        'agency.domain'                     => 'required|unique:agencies,domain',
        'agency.balance'                    => 'required',
        'agency.primary_color'              => 'nullable|min:7|max:7',
        'agency.secondary_color1'           => 'nullable|min:7|max:7',
        'agency.color_footer_text'          => 'nullable|min:7|max:7',
        'agency.color_footer_icons_color'   => 'nullable|min:7|max:7',
        'agency.color_footer_icons_bg'      => 'nullable|min:7|max:7',
        'agency.color_footer_bg_light'      => 'nullable|min:7|max:7',
        'agency.color_footer_bg'            => 'nullable|min:7|max:7',
        'agency.color_body_bg'              => 'nullable|min:7|max:7',
        'agency.color_topbar_bg'            => 'nullable|min:7|max:7',
        'agency.color_top_menu'             => 'nullable|min:7|max:7',
        'agency.color_success'              => 'nullable|min:7|max:7',
        'agency.color_info'                 => 'nullable|min:7|max:7',
        'agency.color_warning'              => 'nullable|min:7|max:7',
        'agency.color_danger'               => 'nullable|min:7|max:7',
        'agency.color_light'                => 'nullable|min:7|max:7',

        'user.name'         => 'required',
        'password'          => 'required',
        'user.email'        => 'required|email|unique:users,email',
    ];
}

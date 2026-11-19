<?php

namespace App\Livewire\Admin\WhiteLabel;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    use WithAgencyMedia;

    public Agency $agency;
    public ?User $user = null;
    public string $password = '';

    public function mount($id)
    {
        $this->agency = Agency::findOrFail($id);
        $this->user = $this->agency->admin;
        $this->mediaCollections = [
            'agency_logo'       => collect($this->agency->logo)->toArray(),
            'agency_print_logo' => collect($this->agency->print_logo)->toArray(),
            'agency_login_bg'   => collect($this->agency->login_bg)->toArray(),
            'agency_search_bg'  => collect($this->agency->search_bg)->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.whitelabel.edit', [
            'agency' => $this->agency
        ])->layout('layouts.admin');
    }

    public function save()
    {
        $this->validate();

        $this->agency->save();

        if ($this->user) {
            if ($this->password !== '') {
                $this->user->password = $this->password;
            }
            $this->user->save();
        }

        $this->syncMedia($this->agency->id);
        Cache::forget('settings');
        session()->flash('message', 'White Label successfully updated.');

        return redirect()->route('admin.whitelabel.index');
    }

    protected function rules(): array
    {
        $color = 'nullable|min:7|max:7';

        return [
            'agency.name'                       => 'required',
            'agency.email'                      => ['required', 'email', Rule::unique('agencies', 'email')->ignore($this->agency->id)],
            'agency.domain'                     => ['required', Rule::unique('agencies', 'domain')->ignore($this->agency->id)],
            'agency.balance'                    => 'required',
            'agency.primary_color'              => $color,
            'agency.secondary_color1'           => $color,
            'agency.color_footer_text'          => $color,
            'agency.color_footer_icons_color'   => $color,
            'agency.color_footer_icons_bg'      => $color,
            'agency.color_footer_bg_light'      => $color,
            'agency.color_footer_bg'            => $color,
            'agency.color_body_bg'              => $color,
            'agency.color_top_menu'             => $color,
            'agency.color_success'              => $color,
            'agency.color_info'                 => $color,
            'agency.color_warning'              => $color,
            'agency.color_danger'               => $color,
            'agency.color_light'                => $color,
            'user.name'                         => $this->user ? 'required' : 'nullable',
            'user.email'                        => $this->user
                ? ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)]
                : 'nullable',
            'password'                          => 'nullable|min:6',
            'mediaCollections.*'                => 'array',
            'mediaCollections.*.*.id'           => 'integer|exists:media,id',
        ];
    }
}

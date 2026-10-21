<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Block;
use App\Models\Section;
use Livewire\Component;

class Home extends Component
{
    public $sections = [];
    public $blocks   = [];

    public function mount()
    {
        $this->sections = Section::query()
            ->where('active', 1)
            ->with('media')
            ->where(function ($q) {
                if(auth()->user()->type != 'admin') {
                    $q
                        ->where('whitelabel_permissions', 'NOT LIKE', '%"' . auth()->user()->agency_id . '"%')
                        ->orWhere('whitelabel_permissions', '')
                        ->orWhereNull('whitelabel_permissions');
                }
            })
            ->where(function ($q) {
                $q
                    ->where('agencies_permissions', 'NOT LIKE', '%' . auth()->user()->email . '%')
                    ->orWhere('agencies_permissions', '')
                    ->orWhereNull('agencies_permissions');
            })
            ->with('categories', function($q) {
                $q->orderBy('position')
                    ->with('media')
                    ->where('active', 1)
                    ->where(function ($q) {
                        if(auth()->user()->type != 'admin') {
                            $q
                                ->where('whitelabel_permissions', 'NOT LIKE', '%"' . auth()->user()->agency_id . '"%')
                                ->orWhere('whitelabel_permissions', '')
                                ->orWhereNull('whitelabel_permissions');
                        }
                    })
                    ->where(function ($q) {
                        $q
                            ->where('agencies_permissions', 'NOT LIKE', '%' . auth()->user()->email . '%')
                            ->orWhere('agencies_permissions', '')
                            ->orWhereNull('agencies_permissions');
                    });
            })
            ->orderBy('position')
            ->get();

        $this->blocks = Block::where([
            'active'    => 1,
            'agency_id' => auth()->user()->agency_id
        ])->whereNull('parent_id')->with('children', function($q) {
            $q->orderBy('position')->where('active', 1);
        })->orderBy('position')->get();
    }

    public function render()
    {
        return view('livewire.frontend.home');
    }
}

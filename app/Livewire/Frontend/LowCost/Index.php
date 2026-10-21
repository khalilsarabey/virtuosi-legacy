<?php

namespace App\Livewire\Frontend\LowCost;

use App\Models\Block;
use App\Models\Section;
use Livewire\Component;

class Index extends Component
{
    public $type = 'roundtrip';
    public $departure;
    public $return;
    public $from;
    public $to;
    public $infant = 0;
    public $child = 0;
    public $adult = 1;
    public $sections = [];
    public $blocks   = [];

    public function mount()
    {
        $this->sections = Section::where('active', 1)
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
                $q->orderBy('position')->where('active', 1)
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
        return view('livewire.frontend.low-cost.index');
    }

    public function populate()
    {

    }

    public function inc($passenger)
    {
        if((int) $this->adult + (int) $this->child + (int) $this->infant == 9) return false;
        if($passenger == 'child' && (int) $this->child + 1 > $this->adult) return false;
        if($passenger == 'infant' && (int) $this->infant + 1 > $this->adult) return false;
        $this->{$passenger}++;
    }

    public function dec($passenger)
    {
        if($passenger == 'adult' && $this->infant > $this->adult - 1) return false;
        if($passenger == 'adult' && $this->child > $this->adult - 1) return false;
        if($passenger == 'adult' && $this->adult > 1) {
            $this->{$passenger}--;
        }
        if($passenger != 'adult' && $this->{$passenger} > 1) {
            $this->{$passenger}--;
        }
    }
}

<?php

namespace App\Livewire\Admin\Hotels\Rules;

use Livewire\Component;
use App\Models\HotelRule;

class Index extends Component
{
    public $hotelRules;

    public function updateRuleOrder($list)
    {
        foreach($list as $item) {
            HotelRule::find($item['value'])->update(['position' => $item['order']]);
        }
    }

    public function deleteRule($id)
    {
        $rule = HotelRule::find($id);
        if ($rule) {
            $rule->delete();
            $this->hotelRules = HotelRule::orderBy('position')->get();
            session()->flash('success', 'Rule deleted successfully!');
        }
    }

    public function render()
    {
        $this->hotelRules = HotelRule::orderBy('position')->get();
        return view('livewire.admin.hotels.rules.index')->layout('layouts.admin', ['title' => 'Hotels']);
    }
}


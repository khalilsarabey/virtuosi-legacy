<?php

namespace App\Http\Livewire\Admin\Hotels\Rules;

use Livewire\Component;
use App\Models\HotelRule;

class Index extends Component
{
    public $rules;

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
            $this->rules = HotelRule::orderBy('position')->get();
            session()->flash('success', 'Rule deleted successfully!');
        }
    }

    public function render()
    {
        $this->rules = HotelRule::orderBy('position')->get();
        return view('livewire.admin.hotels.rules.index')->layout('layouts.admin', ['title' => 'Hotels']);
    }
}


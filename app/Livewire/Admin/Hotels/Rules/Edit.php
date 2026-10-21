<?php

namespace App\Livewire\Admin\Hotels\Rules;

use Livewire\Component;
use App\Models\HotelRule;

class Edit extends Component
{
    public $rule;
    public $hotel_ids = [], $position, $title, $status;
    public $duration_type, $duration, $applicable_to, $destination;
    public $start_date, $end_date;
    public $sub_rules = [];

    protected $rules = [
        'title' => 'required|string',
        'hotel_ids' => 'nullable|array',
        'hotel_ids.*' => 'exists:hotels,id',
        'position' => 'integer',
        'status' => 'boolean',
        'duration_type' => 'string|nullable',
        'duration' => 'nullable|integer',
        'applicable_to' => 'string',
        'destination' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date'=> 'nullable|date',
        'sub_rules' => 'required|array|min:1',
        'sub_rules.*.amount_type' => 'required|in:percentage,fixed',
        'sub_rules.*.amount' => 'required|integer',
        'sub_rules.*.applicable_to' => 'required|in:hotel,ticket,total',
        'sub_rules.*.per_person' => 'nullable|boolean',
    ];

    public function mount($rule)
    {
        $this->rule = HotelRule::query()->findOrFail($rule);
        $this->hotel_ids = $this->rule->hotel_ids ?? [];
        $this->position = $this->rule->position;
        $this->title = $this->rule->title;
        $this->status = $this->rule->status;
        $this->duration_type = $this->rule->duration_type;
        $this->duration = $this->rule->duration;
        $this->applicable_to = $this->rule->applicable_to;
        $this->destination = $this->rule->destination;
        $this->start_date = $this->rule->start_date;
        $this->end_date = $this->rule->end_date;
        $sub_rules = $this->rule->sub_rules_json ?? [];
        if (is_string($sub_rules)) {
            $sub_rules = json_decode($sub_rules, true) ?: [];
        }
        $this->sub_rules = is_array($sub_rules) ? $sub_rules : [];
    }

    public function addSubRule()
    {
        $this->sub_rules[] = [
            'amount_type' => 'percentage',
            'amount' => 0,
            'applicable_to' => 'hotel',
            'per_person' => false,
        ];
    }

    public function removeSubRule($index)
    {
        unset($this->sub_rules[$index]);
        $this->sub_rules = array_values($this->sub_rules);
    }

    public function moveSubRuleUp($index)
    {
        if ($index > 0) {
            $temp = $this->sub_rules[$index - 1];
            $this->sub_rules[$index - 1] = $this->sub_rules[$index];
            $this->sub_rules[$index] = $temp;
        }
    }

    public function moveSubRuleDown($index)
    {
        if ($index < count($this->sub_rules) - 1) {
            $temp = $this->sub_rules[$index + 1];
            $this->sub_rules[$index + 1] = $this->sub_rules[$index];
            $this->sub_rules[$index] = $temp;
        }
    }

    public function update()
    {
        $this->validate();
        //dd($this->hotel_id);
        $this->rule->update([
            'hotel_ids' => $this->hotel_ids,
            'position' => $this->position,
            'title' => $this->title,
            'status' => $this->status,
            'duration_type' => $this->duration_type,
            'duration' => $this->duration,
            'applicable_to' => $this->applicable_to,
            'destination' => $this->destination,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'sub_rules_json' => json_encode($this->sub_rules),
        ]);
        return redirect()->route('admin.hotels.rules.index');
    }

    public function render()
    {
        $hotels_list = \App\Models\Hotel::query()->orderBy('name')->pluck('name', 'id')->toArray();
        return view('livewire.admin.hotels.rules.edit', compact('hotels_list'))->layout('layouts.admin', ['title' => 'Hotels']);
    }
}

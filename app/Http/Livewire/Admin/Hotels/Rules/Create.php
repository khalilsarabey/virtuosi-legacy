<?php

namespace App\Http\Livewire\Admin\Hotels\Rules;

use Livewire\Component;
use App\Models\HotelRule;

class Create extends Component
{
    public $hotel_ids = [], $position = 1, $title, $status = 1;
    public $duration_type = '', $duration, $applicable_to = 'total', $applicable_as = 'percentage', $destination;
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
        'end_date' => 'nullable|date',
        'sub_rules' => 'required|array|min:1',
        'sub_rules.*.amount_type' => 'required|in:percentage,fixed',
        'sub_rules.*.amount' => 'required|integer',
        'sub_rules.*.applicable_to' => 'required|in:hotel,ticket,total',
        'sub_rules.*.per_person' => 'nullable|boolean',
    ];

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

    public function save()
    {
        $this->validate();
        $lastPosition = HotelRule::query()->orderBy('position', 'desc')->first();
        if($lastPosition) {
            $this->position = $lastPosition->position + 1;
        }
        try {
            HotelRule::query()->create([
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
        } catch(\Exception $e) {
            // Optionally handle error
        }
        return redirect()->route('admin.hotels.rules.index');
    }

    public function render()
    {
        $hotels_list = \App\Models\Hotel::query()->orderBy('name')->pluck('name', 'id')->toArray();
        return view('livewire.admin.hotels.rules.create', compact('hotels_list'))->layout('layouts.admin', ['title' => 'Hotels']);
    }
}

<?php

namespace App\Http\Livewire\Admin\Turkish\Sections;

use App\Models\TurkishSection;
use Livewire\Component;

class Index extends Component
{
    public $sections;
    public $renameId;
    public $newName;

    public function render()
    {
        $this->sections = TurkishSection::query()->with('destinations')->orderBy('position')->get();
        return view('livewire.admin.turkish.sections.index')->layout('layouts.admin');
    }

    public function duplicate($id)
    {
        // Use findOrFail for better error handling
        $section = TurkishSection::with([
            'destinations.classes',
            'destinations.airports',
            'destinations.pricing',
            'destinations.pricing.classes',
        ])->findOrFail($id);

        $newSection = $section->replicate();
        $newSection->save();

        // Use each method for a cleaner loop syntax
        $section->destinations->each(function ($destination) use ($newSection) {
            $newDestination = $destination->replicate();
            $newDestination->turkish_section_id = $newSection->id;
            $newDestination->save();

            // Replicate classes
            $destination->classes->each(function ($class) use ($newDestination) {
                $newClass = $class->replicate();
                $newClass->turkish_destination_id = $newDestination->id;
                $newClass->save();
            });

            // Replicate airports
            $destination->airports->each(function ($airport) use ($newDestination, $newSection) {
                $newAirport = $airport->replicate();
                $newAirport->turkish_destination_id = $newDestination->id;
                $newAirport->turkish_section_id = $newSection->id;
                $newAirport->save();
            });

            // Replicate pricing
            $destination->pricing->each(function ($pricing) use ($newDestination) {
                $newPricing = $pricing->replicate();
                $newPricing->turkish_destination_id = $newDestination->id;
                $newPricing->save();

                // Replicate classes
                $pricing->classes->each(function ($class) use ($newPricing) {
                    $newClass = $class->replicate();
                    $newClass->turkish_custom_pricing_id = $newPricing->id;
                    $newClass->save();
                });
            });
        });
    }

    public function rename($id)
    {
        $record = TurkishSection::find($id);
        $this->newName = $record->title;
        $this->renameId = $id;
    }

    public function saveNewName($id)
    {
        if($this->newName && $this->newName != '')
        {
            TurkishSection::find($id)->update(['title' => $this->newName]);
            $this->newName = '';
            $this->renameId = null;
        }
    }

    public function deleteSection($id)
    {
        TurkishSection::find($id)->delete();
    }

    public function toggleStatus($id, $status)
    {
        TurkishSection::find($id)->update([
            'active' => $status == 1 ? 0 : 1
        ]);
    }

    public function updateOrder($list)
    {
        foreach($list as $item) {
            TurkishSection::find($item['value'])->update(['position' => $item['order']]);
        }
    }
}

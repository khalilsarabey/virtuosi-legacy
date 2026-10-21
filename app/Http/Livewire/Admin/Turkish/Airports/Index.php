<?php

namespace App\Http\Livewire\Admin\Turkish\Airports;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\PricingAirline;
use App\Models\TurkishAirport;
use App\Models\TurkishDestination;
use App\Models\TurkishSection;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithConfirmation;
    public $airport_name;
    public string $search = '';
    public $selected_destination;
    protected $paginationTheme = 'bootstrap';
    public int $perPage;
    public array $paginationOptions;

    public $destinations;
    public $destination;

    public $selected = [];
    /**
     * @var null
     */
    public $editing_id;
    public TurkishSection $section;

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount(TurkishSection $section)
    {
        $this->section           = $section;
        $this->perPage           = 50;
        $this->paginationOptions = config('project.pagination.options');
        $this->destinations = TurkishDestination::query()->where('turkish_section_id', $this->section->id)->get();
    }

    public function render()
    {
        $query = TurkishAirport::query()->where('turkish_section_id', $this->section->id)->with('destination');
        if($this->search != '') {
            $query->where('code', 'like', '%' . $this->search . '%')
                ->orWhereHas('destination', function($query){$query->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $airports = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.admin.turkish.airports.index', compact('airports'))->layout('layouts.admin');
    }

    public function assign()
    {
        if(count($this->selected) && is_numeric($this->destination)):
            TurkishAirport::whereIn('id', $this->selected)->update(['turkish_destination_id' => $this->destination]);
            $this->selected = [];
        endif;
    }

    public function delete(TurkishAirport $airport)
    {
        $airport->delete();
    }

    public function add()
    {
        if($this->airport_name && $this->airport_name != '' && $this->selected_destination && $this->selected_destination != '')
        {
            TurkishAirport::create([
                'code'  => $this->airport_name,
                'turkish_destination_id'  => $this->selected_destination,
                'turkish_section_id'  => $this->section->id,
            ]);
            $this->airport_name = '';
            $this->selected_destination = '';
        }
    }

    public function update()
    {
        if($this->airport_name && $this->airport_name != '' &&
            $this->selected_destination && $this->selected_destination != '' &&
            $this->editing_id && $this->editing_id != ''
        ) {
            $airport = TurkishAirport::where('id', $this->editing_id)->first();
            $airport->update([
                'code'  => $this->airport_name,
                'turkish_destination_id'  => $this->selected_destination,
            ]);
        }
        $this->airport_name = null;
        $this->selected_destination = null;
        $this->editing_id = null;
    }

    public function edit($id)
    {
        $airport = TurkishAirport::where('id', $id)->first();
        $this->editing_id = $id;
        $this->airport_name = $airport->code;
        $this->selected_destination = $airport->turkish_destination_id;
    }
}

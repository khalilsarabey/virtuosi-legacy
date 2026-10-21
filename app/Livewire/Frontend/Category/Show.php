<?php

namespace App\Livewire\Frontend\Category;

use App\Http\Services\BookingService;
use App\Models\Category;
use Livewire\Component;
use Log;

class Show extends Component
{

    public $category;
    public $adults      = [];
    public $children    = [];
    public $infants     = [];
    public $total       = [];
    public $flights      = [];

    public function mount($id)
    {
        $this->category = Category::where('slug', $id)
            ->with('flights', function($q) {
                $q->restrict()->with(['prices', 'depFrom', 'depTo'])->whereHas('prices')->orderBy('dep_date');
            })
            ->firstOrFail();
        $this->flights = $this->category->flights;
        foreach($this->category->flights as $key => $value):
            $this->adults[$value->id]     = 1;
            $this->children[$value->id]   = 0;
            $this->infants[$value->id]    = 0;
            $this->total[$value->id]      = 0;
        endforeach;
    }

    public function render()
    {
        return view('livewire.frontend.category.show');
    }

    public function increment($passenger, $type, $flight, $remaining = 0)
    {
        $selectedFlight = $this->flights->find($flight);
        if($selectedFlight->tickets_count === null) {
            $this->flights->find($flight)->loadCount('tickets');
        }
        $selected = ($this->adults[$flight] + $this->children[$flight]);
        if($selected < $remaining) {
            $this->{$passenger}[$flight]++;
            //$this->calculate($type, $flight);
        }
    }

    public function decrement($passenger, $type, $flight)
    {
        if($this->{$passenger}[$flight] > 0) {
            $this->{$passenger}[$flight]--;
        }
        //$this->calculate($type, $flight);
    }

    public function calculate($type, $flight) {
        $bookingService = new BookingService();
        $this->total[(string)$flight] = $bookingService->calculate($type, ['departure' => $flight], ['adults' => $this->adults[$flight], 'children' => $this->children[$flight], 'infants' => $this->infants[$flight]])['total'];
    }
}

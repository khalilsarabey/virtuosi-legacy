<?php

namespace App\Http\Livewire\Frontend\LowCost;

use App\Services\Airlines\AirlineSearchService;
use App\Support\AirlineSupport;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Search extends Component
{
    public $type;
    public $departure;
    public $return;
    public $from;
    public $to;
    public $infant = 0;
    public $child = 0;
    public $adult = 1;
    public $loading;
    protected $data = [];
    public $departures = [];
    public $returns = [];
    public $selected = [];
    public $airline_payloads = [];

    public function mount()
    {
        $this->selected = [];
        $this->loading = true;
        $request = request()->all();
        $this->type         = $request['type'];
        $this->from         = $request['from'];
        $this->to           = $request['to'] ?? null;
        $this->departure    = $request['departure'];
        $this->return       = $request['return'] ?? null;
        $this->adult        = $request['adult'] ?? 1;
        $this->infant       = $request['infant'] ?? 0;
        $this->child        = $request['child'] ?? 0;
        if ($this->adult < 1) return redirect()->back();
        if ($this->adult + $this->child + $this->infant > 9) return redirect()->back();
        if ($this->infant > $this->adult) return redirect()->back();
        if ($this->child > $this->adult) return redirect()->back();
    }

    public function render()
    {
        return view('livewire.frontend.low-cost.search');
    }

    public function populate()
    {
        if ($this->type == 'oneway') {
            $this->data = $this->setOneWay();
        }
        if ($this->type == 'roundtrip') {
            $this->data = $this->setRoundTrip();
        }
        $this->sortResults();
    }

    public function setOneWay()
    {
        $object = new \stdClass();
        $object->flexible_dates     = false;
        $object->dep_from           = $this->from;
        $object->dep_to             = $this->to;
        $object->dep_date           = $this->departure;
        $object->adult              = $this->adult;
        $object->child              = $this->child;
        $object->infant             = $this->infant;
        $data = (new AirlineSupport())->processArray($object);
        return (new AirlineSearchService())->process($data);
    }

    public function setRoundTrip()
    {
        $object = new \stdClass();
        $object->flexible_dates     = false;
        $object->dep_from           = $this->from;
        $object->dep_to             = $this->to;
        $object->dep_date           = $this->departure;
        $object->ret_from           = $this->to;
        $object->ret_to             = $this->from;
        $object->ret_date           = $this->return;
        $object->adult              = $this->adult;
        $object->child              = $this->child;
        $object->infant             = $this->infant;
        $data = (new AirlineSupport())->processArray($object);
        return (new AirlineSearchService())->process($data);
    }

    public function inc($passenger)
    {
        if ((int) $this->adult + (int) $this->child + (int) $this->infant == 9) return false;
        if ($passenger == 'child' && (int) $this->child + 1 > $this->adult) return false;
        if ($passenger == 'infant' && (int) $this->infant + 1 > $this->adult) return false;
        $this->{$passenger}++;
    }

    public function sortResults()
    {
        // Initialize empty arrays for combined departure and return flights
        $combinedDepartures = [];
        $combinedReturns = [];
        $airline_payloads = [];
        if (count($this->data)) :
            foreach ($this->data as $airline => $details) {
                if (count($details)):
                    $airline_payloads[$airline]["payload"] = $details['payload'];
                    $airline_payloads[$airline]["access_token"] = $details["access_token"] ?? null;
                    $airline_payloads[$airline]["session_timestamp"] = $details["session_timestamp"] ?? null;
                    $airline_payloads[$airline]["js_cookie"] = $details["js_cookie"] ?? null;
                    foreach ($details['departure'] as $flight) {
                        $flight['airline'] = $airline;
                        $combinedDepartures[] = $flight;
                    }
                    foreach ($details['return'] as $flight) {
                        $flight['airline'] = $airline;
                        $combinedReturns[] = $flight;
                    }
                endif;
            }

            // Sort the combined arrays by the "price" field
            $this->departures = collect($combinedDepartures)->sortBy('price')->values()->all();
            $this->returns = collect($combinedReturns)->sortBy('price')->values()->all();
            $this->airline_payloads = $airline_payloads;
        endif;
        $this->loading = false;
    }

    public function dec($passenger)
    {
        if ($passenger == 'adult' && $this->infant > $this->adult - 1) return false;
        if ($passenger == 'adult' && $this->child > $this->adult - 1) return false;
        if ($this->{$passenger} > 0) {
            $this->{$passenger}--;
        }
    }

    public function selectDeparture($key): void
    {
        $this->selected['departure']    = $this->departures[$key];
        $this->selected['departure']["payload"] = $this->airline_payloads[$this->selected["departure"]["airline"]]["payload"];
        $this->selected['departure']["access_token"] = $this->airline_payloads[$this->selected["departure"]["airline"]]["access_token"];
        $this->selected['departure']["session_timestamp"] = $this->airline_payloads[$this->selected["departure"]["airline"]]["session_timestamp"];
        $this->selected['departure']["js_cookie"] = $this->airline_payloads[$this->selected["departure"]["airline"]]["js_cookie"];
        $this->selected['return']       = null;
        $this->selected['departure']['key'] = $key;
    }

    public function selectReturn($key): void
    {
        $this->selected['return'] = $this->returns[$key];
        $this->selected['return']["payload"] = $this->airline_payloads[$this->selected["return"]["airline"]]["payload"];
        $this->selected['return']['key'] = $key;
    }
}

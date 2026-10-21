<?php

namespace App\Http\Livewire\Frontend;

use App\Http\Controllers\Connections\Client\ApiClientSearchController;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\HotelRule;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\Component;

class Book extends Component
{
    public $is_package = 0;
    public $query;
    public $departure;
    public $return;
    public $total;
    public $adults, $infants, $children;
    public $countries;
    public $passengers = [];

    public array $adult_prices = [];
    public array $adult_classes = [];
    public array $infant_prices = [];
    public array $infant_classes = [];
    public array $child_prices = [];
    public array $child_classes = [];
    public $ticket_price    = 0;
    public $hotel_price     = 0;
    public array $return_adult_prices = [];
    public array $return_infant_prices = [];
    public array $return_child_prices = [];
    public array $return_adult_classes = [];
    public array $return_infant_classes = [];
    public array $return_child_classes = [];
    private array $rawPrices = [];
    private array $rawReturnPrices = [];
    public $check_in;
    public $check_out;

    public $hotel;
    public $room_selection = [];
    public $data = [];

    protected function validateRequest(Request $request)
    {

        if(!$request->departure && $request->type !== 'hotel' && $request->type !== 'package' && !$request->is_package) abort(404);

        if(($request->type == 'roundtrip' || $request->type == 'multi') && !$request->return) {
            session()->flash('error', 'Missing return flight');
            return redirect()->to(url()->previous());
        }

    }

    public function mount(Request $request)
    {

        $this->validateRequest($request);

        $this->query = $request->all();

        if($request->departure) {
            if(!isset($this->query['api']) || empty($this->query['api'])) {
                $this->departure = Flight::restrict($request->adults + $request->children)
                    ->where('id', $request->departure)
                    ->with(['prices', 'airline', 'depFrom', 'depTo', 'retFrom', 'retTo', 'contract'])
                    ->first()
                    ->toArray();
            } else {
                $departure = ApiClientSearchController::getFlights([
                    'adults' => $request->adults,
                    'children' => $request->children,
                    'infants' => $request->infants,
                    'id' => $request->departure,
                    'api' => $this->query['api']
                ]);
                $this->departure = $departure['data'][0] ?? [];
            }

            if(!$this->departure || count($this->departure) == 0) {
                session()->flash('error', 'There is not enough seats for your request. Please check availability offline with our team.');
                return redirect()->to(url()->previous());
            }
        }

        if($request->type == 'roundtrip' || $request->type == 'multi' ) {

            if(!isset($this->query['api']) || empty($this->query['api'])) {

                $return = Flight::restrict($request->adults + $request->children)
                    ->with(['prices', 'airline', 'depFrom', 'depTo', 'retFrom', 'retTo', 'contract'])
                    ->where('id', $request->return)
                    ->first();
                if($return) $this->return = $return->toArray();

            } else if (auth()->check() && auth()->user()->agency_id == 1) {

                $return = ApiClientSearchController::getFlights([
                    'adults' => $request->adults,
                    'children' => $request->children,
                    'infants' => $request->infants,
                    'id' => $request->return,
                    'api' => $this->query['api']
                ]);
                $this->return = $return['data'][0] ?? [];
            }

            if(!$this->return || count($this->return) == 0) {
                session()->flash('error', 'There is not enough seats for your request. Please check availability offline with our team.');
                return redirect()->to(url()->previous());
            }
        }

        if($request->type !== 'hotel') {
            $this->getPrices();
            $this->getIndividualPrices();
            $this->total = $request->total;
            if($this->total == 0) abort(404);
        }

        $this->countries = countries();

        if((isset($this->query['is_package']) && $this->query['is_package'] == 1) || $this->query['type'] == 'hotel') {

            $this->is_package = 1;

            if(isset($this->query['hotel_id'])):
                if(!isset($this->query['api']) || empty($this->query['api'])) {
                    $this->hotel        = Hotel::query()->where('id', $this->query['hotel_id'])->first();
                } else {
                    $results = ApiClientSearchController::getHotelDetails([
                        'id'         => $this->query['hotel_id'],
                        'api'        => $this->query['api'],
                        'start_date'  => $this->query['startDate'],
                        'end_date'  => $this->query['startDate'],
                    ]);
                    if(count($results)) {
                        $this->hotel = $results['data']['hotel'];
                        $apiRules = $results['data']['rules'];
                        $allRooms = collect($results['data']['hotel']['rooms']);
                    }
                }
                $this->room_selection   = json_decode($this->query['room_selection'], true);
                $this->check_in         = $this->query['startDate'];
                $this->check_out        = $this->query['endDate'];
            endif;

            $roomIds = [];

            foreach($this->room_selection as $room) {
                $roomIds[] = $room['room_id'];
            }

            if(!isset($this->query['api']) || empty($this->query['api'])) {
                $allRooms = collect(Room::whereIn('id', $roomIds)->with(['prices', 'room_type'])->get()->toArray());
            }

            foreach($this->room_selection as $k => $v) {
                $room = $allRooms->where('id', $v['room_id'])->first();
                $price = calculateRoomPrice(
                    $room,
                    $room['prices'],
                    $this->query['startDate'],
                    $this->query['endDate'],
                    ['adult' => $v['requested']['adult'] ?? 0, 'child' => $v['requested']['child'] ?? 0, 'infant' => $v['requested']['infant'] ?? 0]
                );
                $this->room_selection[$k]['price']      = $price;
                $this->room_selection[$k]['room_type']  = $room['room_type']['name'];
                $this->room_selection[$k]['meal_plan']  = $v['meal_plan'] ?? null;
                $this->room_selection[$k]['meal_price'] = $v['meal_price'] ?? null;
                $meal_price = 0;
                $checkIn    = Carbon::parse($this->query['startDate']);
                $checkOut   = Carbon::parse($this->query['endDate']);
                $duration   = $checkOut->diffInDays($checkIn);
                if(isset($v['meal_price']) && $v['meal_price'] > 0) {
                    $child = (int) $v['requested']['child'] > 0 ? (int) $v['requested']['child'] - 1 : 0;
                    $child_price = ($v['meal_price'] * $child) / 2;
                    $adult_price = $v['meal_price'] * $v['requested']['adult'];
                    $meal_price =  $adult_price + $child_price;
                    $meal_price = $meal_price * $duration;
                }
                $this->hotel_price += $price + $meal_price;
                $this->total += $price + $meal_price;
            }
        }

        //Add package addon prices
        if(!isset($this->query['api']) || empty($this->query['api'])) {
            $travellers     = $this->query['adults'] + $this->query['children'];
            $tickets_price  = $this->total - $this->hotel_price;
            $hotel_price    = $this->hotel_price;
            $total_price    = $this->total;
            if(!empty($this->query['hotel_id'])) {
                $this->total    = applyPackageRules($this->query['hotel_id'], $tickets_price, $hotel_price, $total_price, $this->query['startDate'], $this->query['endDate'], $travellers);
            }
        } else {
            $travellers     = $this->query['adults'] + $this->query['children'];
            $tickets_price  = $this->total - $this->hotel_price;
            $hotel_price    = $this->hotel_price;
            $total_price    = $this->total;
            if(!empty($this->query['hotel_id'])) {
                $this->total    = applyApiPackageRules($this->query['hotel_id'], $tickets_price, $hotel_price, $total_price, $this->query['startDate'], $this->query['endDate'], $travellers, $apiRules ?? []);
            }
        }

        if(auth()->user()->type == 'agency' || auth()->user()->type == 'white' || auth()->user()->type == 'white-admin')
        {
            if((int) auth()->user()->balance < (int) $this->total) {
                session()->flash('error', "You don't have enough balance.");
                return redirect()->to(url()->previous());
            }
        }

    }

        public function render()
    {
        return view('livewire.frontend.book');
    }

    public function submit()
    {
        return redirect()->route('frontend.booking.show', '');
    }

    public function getPrices()
    {
        if($this->query['dep_cabin'] == 'economy') {
            $prices = getPrice($this->departure, null, [
                'adult' => $this->query['adults'],
                'infant' => $this->query['infants'],
                'child' => $this->query['children']
            ]);
        } elseif($this->query['dep_cabin'] == 'business') {
            $prices = getBusinessPrice($this->departure, null, [
                'adult' => $this->query['adults'],
                'infant' => $this->query['infants'],
                'child' => $this->query['children']
            ]);
        }

        $this->rawPrices = $prices;

        if($this->return) {
            $return_prices = [];
            if($this->query['ret_cabin'] == 'economy') {
                $return_prices = getPrice($this->return, null, [
                    'adult' => $this->query['adults'],
                    'infant' => $this->query['infants'],
                    'child' => $this->query['children']
                ]);
            } elseif ($this->query['ret_cabin'] == 'business') {
                $return_prices = getBusinessPrice($this->return, null, [
                    'adult' => $this->query['adults'],
                    'infant' => $this->query['infants'],
                    'child' => $this->query['children']
                ]);
            }
            $this->rawReturnPrices = $return_prices;
        }

        foreach($prices as $key => $price):
            $k = Str::plural($key);
            foreach($price as $v):
                $this->{$k} += ($v['price'] * $v['number']);
            endforeach;
        endforeach;

        if($this->return) {
            if($this->query['ret_cabin'] == 'economy') {
                $prices = getPrice($this->return, null, [
                    'adult' => $this->query['adults'],
                    'infant' => $this->query['infants'],
                    'child' => $this->query['children']
                ]);
            } elseif ($this->query['ret_cabin'] == 'business') {
                $prices = getBusinessPrice($this->return, null, [
                    'adult' => $this->query['adults'],
                    'infant' => $this->query['infants'],
                    'child' => $this->query['children']
                ]);
            }

            foreach($prices as $key => $price):
                $k = Str::plural($key);
                foreach($price as $v):
                    $this->{$k} += ($v['price'] * $v['number']);
                endforeach;
            endforeach;
        }
    }

    public function getSupplements()
    {
        $type       = $this->query['type'];
        $adults     = $this->query['adults'];
        $children   = $this->query['children'];

        if ($type == 'oneway') {
            $supplement = $this->departure->oneway_supplements;
        } elseif ($type == 'roundtrip' || $type == 'multi') {
            if ($this->departure->airline->icao != $this->return->airline->icao) {
                $supplement = $this->departure->combinable_supplements;
            } elseif ($type == 'multi') {
                $supplement = $this->departure->multi_supplements;
            } else {
                $supplement = 0;
            }
        } else {
            $supplement = 0;
        }

        $this->adults   += $supplement * $adults;
        $this->children += $supplement * $children;
    }


    public function getIndividualPrices()
    {
        foreach($this->rawPrices as $key => $values):
            if($key == 'adult') {
                foreach($values as $value) {
                    for($i = 0; $i < $value['number']; $i++) {
                        $price = $value['price'];
                        $supplement = 0;
                        if ($this->query['type'] == 'oneway') {
                            $supplement = $this->departure->oneway_supplements ?? $this->departure['oneway_supplements'];
                        } elseif ($this->query['type'] == 'roundtrip' || $this->query['type'] == 'multi') {
                            if ($this->departure['airline']['icao'] != $this->return['airline']['icao']) {
                                $supplement = $this->departure->combinable_supplements ?? $this->departure['combinable_supplements'];
                            } elseif ($this->query['type'] == 'multi') {
                                $supplement = $this->departure->multi_supplements ?? $this->departure['multi_supplements'];
                            }
                        }
                        $price += $supplement;
                        $this->adult_classes[] = $value['class'] ?? '';
                        $this->adult_prices[] = $price;
                    }
                }
            }
            if($key == 'child') {
                foreach($values as $value) {
                    for($i = 0; $i < $value['number']; $i++) {
                        $price = $value['price'];
                        $supplement = 0;
                        if ($this->query['type'] == 'oneway') {
                            $supplement = $this->departure['oneway_supplements'];
                        } elseif ($this->query['type'] == 'roundtrip' || $this->query['type'] == 'multi') {
                            if ($this->departure['airline']['icao'] != $this->return['airline']['icao']) {
                                $supplement = $this->departure['combinable_supplements'];
                            } elseif ($this->query['type'] == 'multi') {
                                $supplement = $this->departure['multi_supplements'];
                            }
                        } //5017
                        $price += $supplement;
                        $this->child_classes[] = $value['class'] ?? '';
                        $this->child_prices[] = $price;
                    }
                }
            }
            if($key == 'infant') {
                foreach($values as $value) {
                    for($i = 0; $i < $value['number']; $i++) {
                        $price = $value['price'];
                        $this->infant_prices[] = $price;
                        $this->infant_classes[] = $value['class'] ?? '';
                    }
                }
            }
        endforeach;
        foreach($this->rawReturnPrices as $key => $values):
            if($key == 'adult') {
                foreach($values as $value) {
                    for($i = 0; $i < $value['number']; $i++) {
                        $price = $value['price'];
                        $supplement = 0;
                        if ($this->query['type'] == 'oneway') {
                            $supplement = $this->departure->oneway_supplements;
                        } elseif ($this->query['type'] == 'roundtrip' || $this->query['type'] == 'multi') {
                            if ($this->departure['airline']['icao'] != $this->return['airline']['icao']) {
                                $supplement = $this->return['combinable_supplements'];
                            } elseif ($this->query['type'] == 'multi') {
                                $supplement = $this->return['multi_supplements'];
                            }
                        }
                        $price += $supplement;
                        $this->return_adult_prices[] = $price;
                        $this->return_adult_classes[] = $value['class'] ?? '';
                    }
                }
            }
            if($key == 'child') {
                foreach($values as $value) {
                    for($i = 0; $i < $value['number']; $i++) {
                        $price = $value['price'];
                        $supplement = 0;
                        if ($this->query['type'] == 'oneway') {
                            $supplement = $this->departure['oneway_supplements'];
                        } elseif ($this->query['type'] == 'roundtrip' || $this->query['type'] == 'multi') {
                            if ($this->departure['airline']['icao'] != $this->return['airline']['icao']) {
                                $supplement = $this->return['combinable_supplements'];
                            } elseif ($this->query['type'] == 'multi') {
                                $supplement = $this->return['multi_supplements'];
                            }
                        }
                        $price += $supplement;
                        $this->return_child_prices[] = $price;
                        $this->return_child_classes[] = $value['class'] ?? '';
                    }
                }
            }
            if($key == 'infant') {
                foreach($values as $value) {
                    for($i = 0; $i < $value['number']; $i++) {
                        $price = $value['price'];
                        $this->return_infant_prices[] = $price;
                        $this->return_infant_classes[] = $value['class'] ?? '';
                    }
                }
            }
        endforeach;
    }
}

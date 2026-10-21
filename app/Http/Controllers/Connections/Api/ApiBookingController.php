<?php

namespace App\Http\Controllers\Connections\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewBookingEmail;
use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\Flight;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApiBookingController extends Controller
{
    private $adults         = 0;
    private $infants        = 0;
    private $children       = 0;
    private $total          = 0;
    private $dep_cabin      = 'economy';
    private $ret_cabin      = 'economy';
    private $supplements    = 0;
    private $ret_class      = null;
    private $dep_class      = null;
    private $dep_adult      = 0;
    private $dep_infant     = 0;
    private $dep_child      = 0;
    private $is_restricted  = false;

    public function makeBooking(Request $request)
    {

        $results = [];

        if ($request->type !== 'hotel') {
            $results = $this->makeFlightBooking($request);
        }

        if ($request->type != 'hotel' && $results['departure']) {
            $seats = $results['departure']->seats - $results['departure']->tickets_count;
            if ($seats < $request->adults + $request->children) return [];
        }

        if ($request->type != 'hotel' && $results['return']) {
            $seats = $results['return']->seats - $results['return']->tickets_count;
            if ($seats < $request->adults + $request->children) return [];
        }

        $restricted = false;

        // Assign flight variables
        if($request->type != 'hotel'):
            $departure          = $results['departure'];
            $return             = $results['return'];
            $total              = $results['total'];
            $supplements        = $results['supplements'];
            $adults_price       = $results['adults'];
            $children_price     = $results['children'];
            $infants_price      = $results['infants'];
            $restricted         = $results['restricted'];
            $type               = $request->type;
            $adults_number      = $request->adults;
            $children_number    = $request->children;
            $infants_number     = $request->infants;
        endif;

        //Create booking model
        $booking                            = new Booking;
        $booking->reference                 = date('Y') .  str_pad(0, 4, "0", STR_PAD_LEFT);
        $booking->user_id                   = auth()->user()->id;
        $booking->status                    = $restricted ? 'On Hold' : 'Confirmed';

        if($request->type != 'hotel'):
            $booking->flight_id             = $departure->id;
            $booking->dep_flight_id         = $departure->id;
            $booking->dep_cabin             = $request->dep_cabin ?? 'economy';
            $booking->ret_cabin             = $request->ret_cabin ?? 'economy';
            $booking->dep_date              = Carbon::createFromFormat(config('project.date_format'), $departure->dep_date)->format('Y-m-d');
            $booking->dep_time              = $departure->dep_time;
            $booking->dep_arr_time          = $departure->dep_arr_time;
            $booking->dep_from_city         = $departure->depFrom->city ?? $departure->dep_from->city;
            $booking->dep_to_city           = $departure->depTo->city ?? $departure->dep_to->city;
            $booking->dep_from_code         = $departure->dep_from_code;
            $booking->dep_to_code           = $departure->dep_to_code;
            $booking->dep_flight_number     = $departure->dep_flight_number;

            $booking->dep_airline_code      = $departure->airline->icao ?? null;
            $booking->dep_airline_name      = $departure->airline->name ?? null;
            $booking->dep_airline_logo      = $departure->airline->thumbnail ?? null;

            $booking->dep_baggage_allowance = $departure->baggage_allowance ?? null;

            $booking->adults                = $adults_number;
            $booking->children              = $children_number;
            $booking->infants               = $infants_number;
            $booking->adults_price          = $adults_price;
            $booking->children_price        = $children_price;
            $booking->infants_price         = $infants_price;
            $booking->supplements           = $supplements;
            $booking->payload               = json_encode($results);
            $booking->type                  = $type;
            $booking->price                 = $total;
            $booking->total                 = $total;
            if($request->is_package && $request->is_package == 1):
                $booking->hotel_id              = $request->hotel_id;
                $booking->price                 = $request->price;
                $booking->total                 = $request->price;
                $booking->hotel_price           = $request->price - $total;
                $booking->flight_price          = $total;
            endif;
        elseif($request->type == 'hotel'):
            $booking->status                = 'Confirmed';
            $booking->type                  = 'hotel';
            $booking->price                 = $request->price;
            $booking->total                 = $request->price;
            $booking->hotel_id              = $request->hotel_id;
            $booking->check_in              = Carbon::createFromFormat(config('project.date_format'), $request->check_in)->format('Y-m-d');
            $booking->check_out             = Carbon::createFromFormat(config('project.date_format'), $request->check_out)->format('Y-m-d');
            $booking->adults                = $request->adults;
            $booking->children              = $request->children;
            $booking->infants               = $request->infants;
            $booking->payload               = json_encode($request->all());
        endif;

        if($request->type == 'fixed') {
            $booking->ret_flight_id         = $departure->id;
            $booking->ret_from_city         = $departure->depTo->city ?? $departure->dep_to->city;
            $booking->ret_to_city           = $departure->depFrom->city ?? $departure->dep_from->city;
            $booking->ret_from_code         = $departure->dep_to_code;
            $booking->ret_to_code           = $departure->dep_from_code;
            $booking->ret_date              = Carbon::createFromFormat(config('project.date_format'), $departure->ret_dep_date)->format('Y-m-d');
            $booking->ret_dep_time          = $departure->ret_dep_time;
            $booking->ret_arr_time          = $departure->ret_arr_time;
            $booking->ret_flight_number     = $departure->ret_flight_number;
            $booking->ret_airline_code      = $departure->airline->icao ?? null;
            $booking->ret_airline_name      = $departure->airline->name ?? null;
            $booking->ret_airline_logo      = $departure->airline->thumbnail ?? null;
            $booking->ret_baggage_allowance = $departure->baggage_allowance ?? null;
        }

        if($request->type == 'roundtrip' || $request->type == 'multi') {
            $booking->ret_flight_id = $return->id;
            $booking->ret_from_city = $return->depFrom->city ?? $return->dep_from->city;
            $booking->ret_to_city   = $return->depTo->city ?? $return->dep_to->city;
            $booking->ret_from_code = $return->dep_from_code;
            $booking->ret_to_code   = $return->dep_to_code;
            $booking->ret_flight_number = $return->dep_flight_number;

            $booking->ret_date      = Carbon::createFromFormat(config('project.date_format'), $return->dep_date)->format('Y-m-d');
            $booking->ret_time      = $return->dep_time;
            $booking->ret_dep_time  = $return->dep_time;
            $booking->ret_arr_time  = $return->dep_arr_time;
            $booking->ret_baggage_allowance = $return->baggage_allowance ?? null;
            $booking->ret_airline_code      = $return->airline->icao ?? null;
            $booking->ret_airline_name      = $return->airline->name ?? null;
            $booking->ret_airline_logo      = $return->airline->thumbnail ?? null;
        }

        $booking_rooms = [];
        $tickets = [];

        foreach($request->reservations as $reservation) {
            if($request->type != 'hotel'):
                $tickets[] = [
                    'flight_id'         => $departure->id,
                    'type'              => $type,
                    'cabin'             => $request->dep_cabin ?? 'economy',
                    'dep_from_city'     => $departure->depFrom->city ?? $departure->dep_from->city,
                    'dep_to_city'       => $departure->depTo->city ?? $departure->dep_to->city,
                    'dep_from_code'     => $departure->dep_from_code,
                    'dep_to_code'       => $departure->dep_to_code,
                    'dep_date'          => Carbon::createFromFormat(config('project.date_format'), $departure->dep_date)->format('Y-m-d'),
                    'dep_time'          => $departure->dep_time,
                    'baggage_allowance' => $departure->baggage_allowance,
                    'dep_arr_time'      => $departure->dep_arr_time,
                    'ret_date'          => $type == 'fixed' ? Carbon::createFromFormat(config('project.date_format'), $departure->ret_dep_date)->format('Y-m-d') : null,
                    'ret_time'          => $type == 'fixed' ? $departure->ret_dep_time : null,
                    'ret_arr_time'      => $type == 'fixed' ? $departure->ret_arr_time : null,
                    "price"             => $reservation['price'] ?? '',
                    "class"             => $reservation['class'] ?? '',
                    "title"             => $reservation['title'] ?? '',
                    'full_name'         => $reservation['first_name'] . ' ' . $reservation['last_name'],
                    "first_name"        => $reservation['first_name'] ?? '',
                    "last_name"         => $reservation['last_name'] ?? '',
                    "phone"             => $reservation['phone'] ?? '',
                    "date_of_birth"     =>  Carbon::createFromFormat(config('project.date_format'), $reservation['date_of_birth'])->format('Y-m-d'),
                    "nationality"       => countries()[$reservation['country']],
                ];
            endif;

            if($request->is_package == 1 || $request->type == 'hotel') {
                $booking_rooms[] = [
                    'room_number'   => $reservation['room_number'] ?? 0,
                    'meal_plan'     => $reservation['meal_plan'] ?? null,
                    'hotel_id'      => $reservation['hotel_id'] ?? '',
                    'room_id'       => $reservation['room_id'] ?? '',
                    'user_id'       => auth()->user()->id,
                    "title"         => $reservation['title'] ?? '',
                    'full_name'     => $reservation['first_name'] . ' ' . $reservation['last_name'],
                    "first_name"    => $reservation['first_name'] ?? '',
                    "last_name"     => $reservation['last_name'] ?? '',
                    "phone"         => $reservation['phone'] ?? '',
                    "check_in"      => $request->type == 'hotel' ? Carbon::createFromFormat(config('project.date_format'), $request->check_in)->format('Y-m-d') : Carbon::createFromFormat(config('project.date_format'), $departure->dep_date)->format('Y-m-d'),
                    "check_out"     => $request->type == 'hotel' ? Carbon::createFromFormat(config('project.date_format'), $request->check_out)->subDay()->format('Y-m-d') : Carbon::createFromFormat(config('project.date_format'), $return->dep_date)->subDay()->format('Y-m-d'),
                    "date_of_birth" => Carbon::createFromFormat(config('project.date_format'), $reservation['date_of_birth'])->format('Y-m-d'),
                    "nationality"   => countries()[$reservation['country']],
                ];
            }

            if($request->type != 'hotel'):
                if($type == 'roundtrip' || $type == 'multi') {
                    $tickets[] = [
                        'cabin'         => $request->ret_cabin ?? 'economy',
                        'flight_id'     => $return->id,
                        'type'          => $type,
                        'dep_from_city' => $return->depFrom->city ?? $return->dep_from->city,
                        'dep_to_city'   => $return->depTo->city ?? $return->dep_to->city,
                        'dep_from_code' => $return->dep_from_code,
                        'dep_to_code'   => $return->dep_to_code,
                        'dep_date'      => Carbon::createFromFormat(config('project.date_format'), $return->dep_date)->format('Y-m-d'),
                        'dep_time'      => $return->dep_time,
                        'dep_arr_time'  => $return->dep_arr_time,
                        'baggage_allowance' => $return->baggage_allowance,
                        'ret_date'      => null,
                        'ret_time'      => null,
                        'ret_arr_time'  => null,
                        "price"         => $reservation['return_price'] ?? '',
                        "class"         => $reservation['return_class'] ?? '',
                        "title"         => $reservation['title'] ?? '',
                        'full_name'     => $reservation['first_name'] . ' ' . $reservation['last_name'],
                        "first_name"    => $reservation['first_name'] ?? '',
                        "last_name"     => $reservation['last_name'] ?? '',
                        "phone"         => $reservation['phone'] ?? '',
                        "date_of_birth" => Carbon::createFromFormat(config('project.date_format'), $reservation['date_of_birth'])->format('Y-m-d'),
                        "nationality"   => countries()[$reservation['country']],
                    ];
                }
            endif;
        }

        DB::beginTransaction();

        try {

            if($request->get('confirmation', 0) == 1) {
                $booking->status = 'On Hold';
            }

            $booking->save();
            $booking->reference = date('Y') . str_pad($booking->id, 4, "0", STR_PAD_LEFT); // Reference with correct id
            $booking->save();

            if(count($tickets)):
                foreach($tickets as &$ticket) $ticket['booking_id'] = $booking->id;
                Ticket::insert($tickets);
            endif;

            if(count($booking_rooms)):
                foreach($booking_rooms as &$room) $room['booking_id'] = $booking->id;
                BookingRoom::insert($booking_rooms);
            endif;

            $booking->load('hotel', 'rooms.room.room_type');

            if (auth()->user()->type == 'agency' || auth()->user()->type == 'white' || auth()->user()->type == 'white-admin' || auth()->user()->type == 'white-user') {
                if(!$restricted) {
                    $user = User::find(auth()->user()->id);
                    $user->decrement('balance', $total);
                    $user->save();
                }
            }

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            die($e->getMessage());
        }
        try {
            Mail::send(new NewBookingEmail($booking->id, auth()->user()->email));
        } catch(\Exception $e) {

        }
        return ['booking' => $booking, 'tickets' => $tickets ?? [], 'booking_rooms' => $booking_rooms ?? []];
    }

    private function makeFlightBooking($request)
    {
        $query      = $request->all();
        $type       = $query['type'];

        $flights = [
            'departure' => $query['departure']  ?? null,
            'return'    => $query['return']     ?? null
        ];

        $passengers = [
            'adults' => 0,
            'infants' => 0,
            'children' => 0,
        ];

        foreach($query['reservations'] as $q):
            if($q['title'] == 'Infant.') {
                $passengers['infants'] += 1;
            } else if ($q['title'] == 'Child.') {
                $passengers['children'] += 1;
            } else {
                $passengers['adults'] += 1;
            }
        endforeach;

        return $this->calculateFlightsPrices($type, $flights, $passengers, $query);
    }


    private function calculateFlightsPrices($type = null, Array $flights = [], Array $passengers = [], $query = [])
    {
        $departure = Flight::restrict()->with(['prices', 'airline'])->find($flights['departure']);

        if(auth()->user()->restricted == 1 || $departure->onhold_bookings == 1) $this->is_restricted = true;

        if(isset($query['dep_cabin']) && $query['dep_cabin'] == 'business') {
            $prices  = getBusinessPrice($departure, null, ['adult' => $passengers['adults'], 'child' => $passengers['children'], 'infant' => $passengers['infants']]);
        } else {
            $prices  = getPrice($departure, null, ['adult' => $passengers['adults'], 'child' => $passengers['children'], 'infant' => $passengers['infants']]);
        }

        $classes = [];

        foreach($prices as $key => $price):
            $k = Str::plural($key);
            foreach($price as $v):
                $this->{$k} += ($v['price'] * $v['number']);
                $classes[] = $v['class'];
            endforeach;
        endforeach;

        $this->dep_class     = implode(",", $classes);

        if($type == 'roundtrip' || $type == 'multi')
        {
            $return = Flight::restrict()->with(['prices', 'airline'])->find($flights['return']);

            if($return) {

                if($return->onhold_bookings == 1) $this->is_restricted = true;

                if(isset($query['ret_cabin']) && $query['ret_cabin'] == 'business') {
                    $prices  = getBusinessPrice($return, null, ['adult' => $passengers['adults'], 'child' => $passengers['children'], 'infant' => $passengers['infants']]);
                } else {
                    $prices  = getPrice($return, null, ['adult' => $passengers['adults'], 'child' => $passengers['children'], 'infant' => $passengers['infants']]);
                }

                $classes = [];

                foreach($prices as $key => $price):
                    $k = Str::plural($key);
                    foreach($price as $v):
                        $this->{$k} += ($v['price'] * $v['number']);
                        $classes[] = $v['class'];
                    endforeach;
                endforeach;

                $this->ret_class = implode(",", $classes);
            }
        }

        if($type == 'multi')
        {
            $this->supplements += $departure->multi_supplements * $passengers['adults'];
            $this->supplements += $departure->multi_supplements * $passengers['children'];
            if(isset($return)) {
                $this->supplements += $return->multi_supplements * $passengers['adults'];
                $this->supplements += $return->multi_supplements * $passengers['children'];
            }
        }

        if($type == 'oneway')
        {
            $this->supplements += $departure->oneway_supplements * $passengers['adults'];
            $this->supplements += $departure->oneway_supplements * $passengers['children'];
        }

        if($type == 'roundtrip' || $type == 'multi')
        {
            if(isset($departure) && isset($return))
            {
                if($departure->airline->icao != $return->airline->icao)
                {
                    $this->supplements  = 0;
                    $this->supplements += $departure->combinable_supplements * $passengers['adults'];
                    $this->supplements += $departure->combinable_supplements * $passengers['children'];
                    $this->supplements += $return->combinable_supplements * $passengers['adults'];
                    $this->supplements += $return->combinable_supplements * $passengers['children'];
                }
            }
        }

        if($type == 'fixed') {
            $this->ret_class = getPrice($departure, 'class') ?? '';
        }

        $this->total = $this->adults + $this->children + $this->infants + $this->supplements;

        return [
            'dep_class'     => $this->dep_class,
            'ret_class'     => $this->ret_class,
            'departure'     => $departure,
            'return'        => $return ?? null,
            'total'         => $this->total,
            'supplements'   => $this->supplements,
            'adults'        => $this->adults,
            'children'      => $this->children,
            'infants'       => $this->infants,
            'dep_adult'     => $this->dep_adult,
            'dep_infant'    => $this->dep_infant,
            'dep_child'     => $this->dep_child,
            'restricted'    => $this->is_restricted,
            'prices'        => []
        ];
    }

    public function getUpdates($id)
    {
        $bookingId = substr($id, 4);
        $booking = Booking::query()->where([
            'id' => $bookingId,
        ])->with([
            'tickets', 'rooms', 'tickets'
        ])->first();
        return $booking;
    }

}

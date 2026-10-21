<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Connections\Client\ApiClientBookingController;
use App\Http\Services\BookingService;
use App\Mail\NewBookingEmail;
use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingsController extends Controller
{
    public function store(Request $request)
    {

        $api = isset($request->api) && is_numeric($request->api) && $request->api > 0 ? $request->api : null;

        if($api) {
            $booking = $this->apiStore($request);
            if(!isset($booking['error'])) {
                return redirect()->route('frontend.booking.show', $booking->id);
            } else {
                return redirect('/')->with('error', $booking['error']);
            }
        } else {

            if($request->type !== 'hotel') {
                $service = new BookingService();
                $results = $service->book($request->all());
                if(!is_array($results)) return redirect('/');
            }

            if($request->type != 'hotel' && $results['departure']) {
                $seats = $results['departure']->seats - $results['departure']->tickets_count;
                if($seats < $request->adults + $request->children) {
                    return redirect()->back();
                }
            }

            if($request->type != 'hotel' && $results['return']) {
                $seats = $results['return']->seats - $results['return']->tickets_count;
                if($seats < $request->adults + $request->children) {
                    return redirect()->back();
                }
            }

            $restricted = false;

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
            $booking->user_id                   = auth()->user()->id;
            $booking->status                    = $restricted ? 'On Hold' : 'Confirmed';

            if($request->type != 'hotel'):
                $booking->flight_id             = $departure->id;
                $booking->dep_flight_id         = $departure->id;
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
                $booking->dep_cabin             = $request->dep_cabin ?? null;
                $booking->ret_cabin             = $request->ret_cabin ?? null;
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
                $booking->ret_flight_id         = $api ? null : $departure->id;
                $booking->ret_from_city         = $departure->depTo->city ?? $departure->dep_to->city ?? null;
                $booking->ret_to_city           = $departure->depFrom->city ?? $departure->dep_from->city ?? null;
                $booking->ret_from_code         = $departure->dep_to_code ?? null;
                $booking->ret_to_code           = $departure->dep_from_code ?? null;
                $booking->ret_date              = Carbon::createFromFormat(config('project.date_format'), $departure->ret_dep_date)->format('Y-m-d');
                $booking->ret_dep_time          = $departure->ret_dep_time ?? null;
                $booking->ret_arr_time          = $departure->ret_arr_time ?? null;
                $booking->ret_flight_number     = $departure->ret_flight_number ?? null;
                $booking->ret_airline_code      = $departure->airline->icao ?? null;
                $booking->ret_airline_name      = $departure->airline->name ?? null;
                $booking->ret_airline_logo      = $departure->airline->thumbnail ?? null;
                $booking->ret_baggage_allowance = $departure->baggage_allowance ?? null;
            }

            if($request->type == 'roundtrip' || $request->type == 'multi') {
                $booking->ret_flight_id     = $api ? null : $return->id;
                $booking->ret_from_city     = $return->depFrom->city ?? $return->dep_from->city ?? null;
                $booking->ret_to_city       = $return->depTo->city ?? $return->dep_to->city ?? null;
                $booking->ret_from_code     = $return->dep_from_code ?? null;
                $booking->ret_to_code       = $return->dep_to_code ?? null;
                $booking->ret_flight_number = $return->dep_flight_number ?? null;

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
                        'booking_id'        => null,
                        'flight_id'         => $departure->id,
                        'type'              => $type,
                        'dep_from_city'     => $departure->depFrom->city ?? $departure->dep_from->city,
                        'dep_to_city'       => $departure->depTo->city ?? $departure->dep_to->city,
                        'dep_from_code'     => $departure->dep_from_code,
                        'dep_to_code'       => $departure->dep_to_code,
                        'dep_date'          => Carbon::createFromFormat(config('project.date_format'), $departure->dep_date)->format('Y-m-d'),
                        'dep_time'          => $departure->dep_time,
                        'baggage_allowance' => $departure->baggage_allowance ?? '',
                        'dep_arr_time'      => $departure->dep_arr_time,
                        'ret_date'          => $type == 'fixed' ? Carbon::createFromFormat(config('project.date_format'), $departure->ret_dep_date)->format('Y-m-d') : null,
                        'ret_time'          => $type == 'fixed' ? $departure->ret_dep_time : null,
                        'ret_arr_time'      => $type == 'fixed' ? $departure->ret_arr_time : null,
                        "price"             => $reservation['price'],
                        "class"             => $reservation['class'],
                        "title"             => $reservation['title'],
                        'full_name'         => $reservation['first_name'] . ' ' . $reservation['last_name'],
                        "first_name"        => $reservation['first_name'],
                        "last_name"         => $reservation['last_name'],
                        "phone"             => $reservation['phone'],
                        "cabin"             => $request->dep_cabin,
                        "date_of_birth"     =>  Carbon::createFromFormat(config('project.date_format'), $reservation['date_of_birth'])->format('Y-m-d'),
                        "nationality"       => countries()[$reservation['country']],
                    ];
                endif;
                if($request->is_package == 1 || $request->type == 'hotel') {
                    $booking_rooms[] = [
                        'booking_id'    => null,
                        'room_number'   => $reservation['room_number'] ?? 0,
                        'meal_plan'     => $reservation['meal_plan'] ?? null,
                        'hotel_id'      => $reservation['hotel_id'],
                        'room_id'       => $reservation['room_id'],
                        'user_id'       => auth()->user()->id,
                        "title"         => $reservation['title'],
                        'full_name'     => $reservation['first_name'] . ' ' . $reservation['last_name'],
                        "first_name"    => $reservation['first_name'],
                        "last_name"     => $reservation['last_name'],
                        "phone"         => $reservation['phone'],
                        "check_in"      => $request->type == 'hotel' ? Carbon::createFromFormat(config('project.date_format'), $request->check_in)->format('Y-m-d') : Carbon::createFromFormat(config('project.date_format'), $departure->dep_date)->format('Y-m-d'),
                        "check_out"     => $request->type == 'hotel' ? Carbon::createFromFormat(config('project.date_format'), $request->check_out)->subDay()->format('Y-m-d') : Carbon::createFromFormat(config('project.date_format'), $return->dep_date)->subDay()->format('Y-m-d'),
                        "date_of_birth" => Carbon::createFromFormat(config('project.date_format'), $reservation['date_of_birth'])->format('Y-m-d'),
                        "nationality"   => countries()[$reservation['country']],
                    ];
                }
                if($request->type != 'hotel'):
                    if($type == 'roundtrip' || $type == 'multi') {
                        $tickets[] = [
                            'booking_id'    => null,
                            'flight_id'     => $return->id,
                            'type'          => $type,
                            'dep_from_city' => $return->depFrom->city ?? $return->dep_from->city,
                            'dep_to_city'   => $return->depTo->city ?? $return->dep_to->city,
                            'dep_from_code' => $return->dep_from_code,
                            'dep_to_code'   => $return->dep_to_code,
                            'baggage_allowance' => $return->baggage_allowance ?? '',
                            'dep_date'      => Carbon::createFromFormat(config('project.date_format'), $return->dep_date)->format('Y-m-d'),
                            'dep_time'      => $return->dep_time,
                            'dep_arr_time'  => $return->dep_arr_time,
                            'ret_date'      => null,
                            'ret_time'      => null,
                            'ret_arr_time'  => null,
                            "price"         => $reservation['return_price'],
                            "class"         => $reservation['return_class'] ?? '',
                            "title"         => $reservation['title'],
                            'full_name'     => $reservation['first_name'] . ' ' . $reservation['last_name'],
                            "first_name"    => $reservation['first_name'],
                            "last_name"     => $reservation['last_name'],
                            "phone"         => $reservation['phone'],
                            "cabin"         => $request->ret_cabin,
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

                // Assign the newly created booking ID to tickets
                foreach ($tickets as &$ticket) {
                    $ticket['booking_id'] = $booking->id;
                }

                // Assign the newly created booking ID to booking rooms
                foreach ($booking_rooms as &$room) {
                    $room['booking_id'] = $booking->id;
                }

                if (count($tickets)) {
                    Ticket::insert($tickets);
                }

                if (count($booking_rooms)) {
                    BookingRoom::insert($booking_rooms);
                }

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
            }
            try {
                if(config('app.enable_emails')):
                    Mail::queue(new NewBookingEmail($booking->id, auth()->user()->email));
                endif;
            } catch(\Exception $e) {

            }
            return redirect()->route('frontend.booking.show', $booking->id);
        }
    }

    private function storeBookingFromAPI($request)
    {

    }

    public function show($id)
    {
        $booking = \App\Models\Booking::where('id', $id);

        if (auth()->user()->type != 'admin' && auth()->user()->type != 'white-admin')
        {
            $booking->where('user_id', auth()->user()->id);
        }
        if(auth()->user()->type != 'admin' && auth()->user()->type != 'user')
        {
            $booking->where('agency_id', auth()->user()->agency_id);
        }
        $booking->with([
            'tickets',
            'rooms.hotel',
            'rooms.room.room_type',
            'depFlight.airline',
            'retFlight.airline',
            'user'
        ]);
        $booking = $booking->firstOrFail();
        return view('frontend.booking.show', compact('booking'));
    }

    public function print($id)
    {
        $booking = \App\Models\Booking::where('id', $id);

        if (auth()->user()->type != 'admin' && auth()->user()->type != 'white-admin' && auth()->user()->type != 'accounting')
        {
            $booking->where('user_id', auth()->user()->id);
        }
        if(auth()->user()->type != 'admin' && auth()->user()->type != 'user' && auth()->user()->type != 'accounting')
        {
            $booking->where('agency_id', auth()->user()->agency_id);
        }
        $booking->with('tickets');
        $booking->with('rooms.hotel');
        $booking->with('rooms.room.room_type');
        $booking->with('depFlight');
        $booking->with('retFlight');
        $booking->with('user');
        $booking = $booking->firstOrFail();
        return view('frontend.booking.print', compact('booking'));
    }

    public function invoice($id)
    {

        $booking = \App\Models\Booking::where('id', $id);

        if (auth()->user()->type != 'admin' && auth()->user()->type != 'white-admin' && auth()->user()->type != 'accounting')
        {
            $booking->where('user_id', auth()->user()->id);
        }
        if(auth()->user()->type != 'admin' && auth()->user()->type != 'user' && auth()->user()->type != 'accounting')
        {
            $booking->where('agency_id', auth()->user()->agency_id);
        }
        $booking->with('tickets');
        $booking->with('rooms.hotel');
        $booking->with('rooms.room.room_type');
        $booking->with('depFlight');
        $booking->with('retFlight');
        $booking->with('user');
        $booking = $booking->firstOrFail();
        return view('frontend.booking.invoice', compact('booking'));
    }

    public function airline_invoice($id)
    {
        if(auth()->user()->agency_id != 1) {
            abort('Access Denied');
        }
        $booking = \App\Models\AirlineBooking::where('id', $id);
        if (auth()->user()->type == 'agency') {
            $booking->where('user_id', auth()->user()->id);
        }
        $booking = $booking->firstOrFail();
        return view('frontend.booking.airline-invoice', compact('booking'));
    }

    private function apiStore($request)
    {
        //Make booking on the api first
        $results = ApiClientBookingController::sendBookingRequest($request->all());

        if(isset($results['error'])) die($results['error']);

        if(!isset($results['booking'])) die('No booking found');

        $bookingResult = $results['booking'];

        DB::beginTransaction();

        try {
            $booking = new Booking;
            $booking->connection_reference = $bookingResult['reference'];
            $booking->agency_id         = 1;
            $booking->user_id           = auth()->user()->id;
            $booking->payload           = json_encode($request->all());
            $booking->status            = $bookingResult['status'];
            $booking->dep_date          = $bookingResult['dep_date'];
            $booking->dep_time          = $bookingResult['dep_time'];
            $booking->dep_arr_time      = $bookingResult['dep_arr_time'];
            $booking->dep_from_city     = $bookingResult['dep_from_city'];
            $booking->dep_to_city       = $bookingResult['dep_to_city'];
            $booking->dep_from_code     = $bookingResult['dep_from_code'];
            $booking->dep_to_code       = $bookingResult['dep_to_code'];
            $booking->dep_flight_number = $bookingResult['dep_flight_number'] ?? null;
            $booking->dep_airline_code  = $bookingResult['dep_airline_code'] ?? null;
            $booking->dep_airline_name  = $bookingResult['dep_airline_name'] ?? null;
            $booking->dep_airline_logo  = $bookingResult['dep_airline_logo'] ?? null;
            $booking->adults            = $bookingResult['adults'];
            $booking->children          = $bookingResult['children'];
            $booking->infants           = $bookingResult['infants'];
            $booking->adults_price      = $bookingResult['adults_price'];
            $booking->children_price    = $bookingResult['children_price'];
            $booking->infants_price     = $bookingResult['infants_price'];
            $booking->supplements       = $bookingResult['supplements'];
            $booking->type              = $bookingResult['type'];
            $booking->price             = $bookingResult['price'];
            $booking->hotel_price       = $bookingResult['hotel_price'] ?? null;
            $booking->flight_price      = $bookingResult['flight_price'] ?? null;
            $booking->total             = $bookingResult['total'];
            $booking->ret_from_city     = $bookingResult['ret_from_city'] ?? null;
            $booking->ret_to_city       = $bookingResult['ret_to_city'] ?? null;
            $booking->ret_from_code     = $bookingResult['ret_from_code'] ?? null;
            $booking->ret_to_code       = $bookingResult['ret_to_code'] ?? null;
            $booking->ret_flight_number = $bookingResult['ret_flight_number'] ?? null;
            $booking->ret_date          = $bookingResult['ret_date'] ?? null;
            $booking->ret_time          = $bookingResult['ret_time'] ?? null;
            $booking->ret_dep_time      = $bookingResult['ret_dep_time'] ?? null;
            $booking->ret_arr_time      = $bookingResult['ret_arr_time'] ?? null;
            $booking->ret_airline_code  = $bookingResult['ret_airline_code'] ?? null;
            $booking->ret_airline_name  = $bookingResult['ret_airline_name'] ?? null;
            $booking->ret_airline_logo  = $bookingResult['ret_airline_logo'] ?? null;
            $booking->dep_cabin  = $bookingResult['dep_cabin'] ?? 'economy';
            $booking->ret_cabin  = $bookingResult['ret_cabin'] ?? 'economy';
            $booking->source            = 'connection';
            $booking->connection_id     = $results['source'];
            $booking->save();

            if ($results['tickets']) {
                $records = $results['tickets']; // Assume this is an array of tickets
                foreach ($records as &$record) {
                    unset($record['flight_id']);
                    unset($record['id']);
                    $record['booking_id'] = $booking->id;
                }
                Ticket::insert($records); // Insert all records at once
            }

            if (isset($results['booking']['rooms']) && count($results['booking']['rooms']) > 0) {
                $bookingRooms = $results['booking']['rooms'];
                $rooms   = [];
                foreach ($bookingRooms as $bookingRoom) {
                    $room = $bookingRoom;
                    unset($room['id']);
                    unset($room['room_id']);
                    unset($room['hotel_id']);
                    unset($room['deleted_at']);
                    unset($room['created_at']);
                    unset($room['updated_at']);
                    $room['room_name'] = $room['room']['room_type']['name'] ?? '';
                    $room['user_id'] = auth()->user()->id;
                    $room['booking_id'] = $booking->id;
                    unset($room['room']);
                    $rooms[] = $room;
                }
                BookingRoom::insert($rooms); // Insert all records at once
                $booking->hotel_name = $results['booking']['hotel']['name'] ?? '';
                $booking->hotel_location = $results['booking']['hotel']['location'] ?? '';
                $booking->hotel_check_in = $results['booking']['hotel']['check_in'] ?? '';
                $booking->hotel_check_out = $results['booking']['hotel']['check_out'] ?? '';
                $booking->save();

                $booking->reference = date('Y') . str_pad($booking->id, 4, "0", STR_PAD_LEFT);
                $booking->save();
            }

            DB::commit();

            try {
                Mail::send(new NewBookingEmail($booking->id, auth()->user()->email));
            } catch (\Exception $e) {
                // Handle mail sending error if needed
            }
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }
}

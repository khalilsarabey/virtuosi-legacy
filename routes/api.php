<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Connections\Api\ApiBookingController;
use App\Http\Controllers\Connections\Api\ApiSearchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

//use App\Http\Controllers\Api\V1\Admin\UserApiController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/export-room-types/admin1974', function() {
    $roomTypes = \App\Models\RoomType::query()->get();
    return response()->json(['room_types' => $roomTypes]);
});

Route::get('/export-hotels/admin1974', function () {

    $hotels = \App\Models\Hotel::query()->where('active', 1)
    ->with(['rooms.prices'])
    ->get();

    $results = [];
    foreach($hotels as $hotel) {
        $record = [];
        $record['id'] = $hotel->id;
        $record['name'] = $hotel->name;
        $record['slug'] = Str::slug($hotel->name);
        $record['type'] = 'hotel';
        $record['created_at'] = $hotel->created_at;
        $record['description'] = $hotel->description;
        $record['confirmation'] = $hotel->confirmation;
        $record['requires_confirmation'] = $hotel->requires_confirmation;
        $record['currency'] = $hotel->currency;
        $record['active'] = $hotel->active;
        $record['check_in'] = $hotel->check_in;
        $record['check_out'] = $hotel->check_out;
        $record['address'] = $hotel->address;
        $record['rating'] = $hotel->rating;
        $record['city'] = $hotel->location;
        $record['meal_plans'] = [];
        if(is_numeric($hotel->bb)) {
            $record['meal_plans']['BB'] = [
                'title' => 'Bed & Breakfast',
                'price' => $hotel->bb,
            ];
        }
        if(is_numeric($hotel->fb)) {
            $record['meal_plans']['FB'] = [
                'title' => 'Full Board',
                'price' => $hotel->fb,
            ];
        }
        if(is_numeric($hotel->ai)) {
            $record['meal_plans']['AI'] = [
                'title' => 'All Inclusive',
                'price' => $hotel->ai,
            ];
        }
        if(is_numeric($hotel->sai)) {
            $record['meal_plans']['SAI'] = [
                'title' => 'Soft All Inclusive',
                'price' => $hotel->sai,
            ];
        }
        if(is_numeric($hotel->uai)) {
            $record['meal_plans']['UAI'] = [
                'title' => 'Ultra All Inclusive',
                'price' => $hotel->uai,
            ];
        }
        if(is_numeric($hotel->hb)) {
            $record['meal_plans']['HB'] = [
                'title' => 'Half Board',
                'price' => $hotel->hb,
            ];
        }
        $rooms = [];
        foreach($hotel->rooms as $room) {
            $roomRecord = [];
            if(!$room['deleted_at']) {
                $roomRecord['id'] = $room['id'];
                $roomRecord['hotel_id'] = $room['hotel_id'];
                $roomRecord['units'] = $room['units'];
                $roomRecord['max_capacity'] = $room['max_capacity'];
                $roomRecord['start_date'] = $room['start_date'];
                $roomRecord['end_date'] = $room['end_date'];
                $roomRecord['currency'] = $room['currency'];
                $roomRecord['room_type_id'] = $room['room_type_id'];
                $roomRecord['adult_pricing'] = $room['adult_pricing'];
                $roomRecord['adult_capacity'] = $room['adult_capacity'];
                $roomRecord['child_pricing'] = $room['child_pricing'];
                $roomRecord['child_capacity'] = $room['child_capacity'];
                $roomRecord['infant_pricing'] = $room['infant_pricing'];
                $roomRecord['infant_capacity'] = $room['infant_capacity'];
                $roomRecord['active'] = $room['active'];
                $roomRecord['prices'] = $room['prices'];
            }
            $rooms[] = $roomRecord;
        }
        $record['rooms'] = $rooms;
        $results[] = $record;
    }
    return response()->json(['hotels' => $results]);
});

Route::get('/export-bookings/admin1974', function () {
    $with = (bool)request()->query('with');
    $bookings = \App\Models\Booking::query()
        ->with(['user', 'tickets', 'hotel', 'rooms', 'tickets.flight', 'rooms'])
        ->where(function($q) {
            $q->where('bookings.dep_date', '>=', now())
            ->orWhere('bookings.ret_date', '>=', now());
        })
        ->when(true, function (\Illuminate\Database\Eloquent\Builder $query) use ($with) {
            $with
                ? $query->whereNotNull('hotel_id')
                : $query->whereNull('hotel_id');
        })
        ->get();
    return response()->json(['bookings' => $bookings]);
});
//22-01-2026
Route::get('/export-flights/admin1974', function () {
    $contracts = \App\Models\Contract::query()->get();

    $flights = \App\Models\Flight::query()
        ->with(['airline', 'prices', 'tickets'])
        ->where('dep_date', '>=', '2026-01-20')
        //->where('dep_date', '<=', now())
        ->get();
    $results = [];
    foreach($flights as $flight) {
        $record = [];
        $record['id'] = $flight->id;
        $record['airline'] = $flight->airline->iata;
        $record['flight_number'] = $flight->dep_flight_number;
        $record['departure_date'] = $flight->dep_date;
        $record['departure_time'] = $flight->dep_time;
        $record['arrival_time'] = $flight->dep_arr_time;
        $record['departure_airport'] = $flight->dep_from_code;
        $record['arrival_airport'] = $flight->dep_to_code;
        $record['departure_city'] = $flight->dep_from_code;
        $record['arrival_city'] = $flight->dep_to_code;
        $record['departure_country'] = $flight->dep_from_code == 'BEY' ? 'LB' : 'EG';
        $record['arrival_country'] = $flight->dep_to_code == 'BEY' ? 'LB' : 'EG';
        $record['baggage_allowance'] = $flight->baggage_allowance;
        $record['oneway_supplements'] = $flight->oneway_supplements;
        $record['contract_id'] = $flight->contract_id == 3 ? 1 : null;
        $record['multi_supplements'] = $flight->multi_supplements;
        $record['combined_supplements'] = $flight->combinable_supplements;
        $record['package_supplements'] = $flight->package_supplements;
        $record['economy_seats'] = $flight->seats;
        $record['business_seats'] = 0;
        $record['type'] = $flight->type;
        $record['policies'] = ['notes' => null, 'rules' => null, 'is_non_refundable' => true];
        $record['remaining_seats_display'] = $flight->remaining_seats_display;
        foreach($flight->prices as $price) {
            $record['prices'][] = [
                'id' => $price->id,
                'flight_id' => $price->flight_id,
                'class' => $price->class,
                'seats' => $price->seats,
                'adult_net' => 0,
                'child_net' => 0,
                'infant_net' => 0,
                'adult' => $price->adult,
                'child' => $price->child,
                'infant' => $price->infant,
                'adult_package' => $price->package_adult,
                'child_package' => $price->package_child,
                'infant_package' => $price->package_infant,
                'position' => $price->position,
                'cabin' => $price->type,
            ];
        }
        $results[] = $record;
    }
    return response()->json(['flights' => $results, 'contracts' => $contracts]);
});

Route::get('/export-agencies/admin1974', function () {
    $users = \App\Models\User::query()
        ->whereIn('type', ['agency', 'user', 'api'])
        ->select([
            'id', 'type', 'name', 'email', 'password', 'balance',
            'active', 'is_approved', 'api', 'notes',
            'blocked_connections', 'created_at', 'pricing_class_id',
        ])->get()->makeVisible('password');

    return response()->json(['users' => $users]);
});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('heartbeat', function () { return ['status' => 'ok']; });
    Route::get('search/locations', [ApiSearchController::class, 'locations']);
    //Flights
    Route::get('search/flights', [ApiSearchController::class, 'flights']);
    Route::get('flights', [ApiSearchController::class, 'getFlights']);
    Route::get('search/flexible', [ApiSearchController::class, 'flexible']);
    Route::get('search/grid', [ApiSearchController::class, 'grid']);
    //Hotels
    Route::get('search/hotels', [ApiSearchController::class, 'hotels']);
    Route::get('hotel', [ApiSearchController::class, 'getHotelDetails']);
    Route::get('hotel/rooms', [ApiSearchController::class, 'getHotelRooms']);
    //Booking
    Route::get('book', [ApiBookingController::class, 'makeBooking']);
    Route::get('get_booking_update', [ApiBookingController::class, 'getUpdates']);
});

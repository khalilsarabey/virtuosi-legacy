<?php
namespace App\Services\Local;

use App\Interfaces\HotelServiceInterface;
use App\Models\Hotel;
use App\Models\HotelRule;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class HotelService implements HotelServiceInterface
{
//    public static function search_hotels($destination = '', $checkIn, $checkOut, $rooms, $hotel = null): array
//    {
//
//        $checkIn = Carbon::parse($checkIn);
//        $checkOut = Carbon::parse($checkOut)->subDay(); // Adjust the check-out date by subtracting one day
//
//        $availableHotels = Hotel::query();
//        if(!empty($destination)) $availableHotels->where('location', $destination);
//        if($hotel && !empty($hotel)) $availableHotels->where('id', $hotel);
//        $availableHotels->where('active', 1);
//        $availableHotels->with('city');
//        $availableHotels->with(['rooms' => function ($query) use ($rooms, $checkOut, $checkIn, $destination) {
//            $query->where('active', 1);
//            $query->whereDate('start_date', '<=',$checkIn);
//            $query->whereDate('end_date', '>=',$checkOut);
//            $query->where(function($q) use ($rooms) {
//                foreach($rooms as $k => $room) {
//                    if($k == 0) {
//                        $q->where(function ($query) use ($room) {
//                            $query->where('adult_capacity', '>=', $room['adult']);
//                            $query->where('child_capacity', '>=', $room['child']);
//                            $query->where('infant_capacity', '>=', $room['infant']);
//                        });
//                    } else {
//                        $q->orWhere(function ($query) use ($room) {
//                            $query->where('adult_capacity', '>=', $room['adult']);
//                            $query->where('child_capacity', '>=', $room['child']);
//                            $query->where('infant_capacity', '>=', $room['infant']);
//                        });
//                    }
//                }
//            });
//            $query->withCount(['bookings as bookings_count' => function ($q)  use ($checkIn, $checkOut) {
//                $q->where('room_number', 1)
//                    ->whereBetween('check_in', [$checkIn, $checkOut])
//                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
//                    ->orWhere(function ($query) use ($checkIn, $checkOut) {
//                        $query->where('check_in', '<', $checkIn)
//                            ->where('check_out', '>', $checkOut);
//                    });
//            }]);
//            $query->havingRaw("units > bookings_count");
//            $query->with(['prices', 'room_type']);
//        }]);
//
//        $availableHotels = $availableHotels->get()->filter(function ($singleHotel) use ($rooms) {
//            $totalUnitsAvailable = $singleHotel->rooms->sum(function($room) {
//                return $room->units - $room->bookings_count;
//            });
//            return $totalUnitsAvailable >= count($rooms);
//        })->all();
//
//        $hotels = collect();
//
//        foreach ($availableHotels as $availableHotel) {
//            // Ensure rooms are unique
//            $uniqueRooms = $availableHotel->rooms->unique('room_type_id'); // Distinct all rooms by 'room_type_id'
//
//            // Optionally, reindex the collection to avoid potential issues with non-sequential keys
//            $availableHotel->setRelation('rooms', $uniqueRooms->values());
//
//            // Push the hotel with its unique rooms into the collection
//            $hotels->push($availableHotel);
//        }
//
//        return $hotels->toArray();
//    }

    public static function search_hotels($destination = '', $checkIn, $checkOut, $rooms, $hotel = null): array
    {
        $checkIn = Carbon::parse($checkIn);
        $checkOut = Carbon::parse($checkOut)->subDay(); // Adjust the check-out date by subtracting one day

        $availableHotels = Hotel::query();
        $availableHotels->restrict();
        if (!empty($destination)) $availableHotels->where('location', $destination);
        if ($hotel && !empty($hotel)) $availableHotels->where('id', $hotel);
        $availableHotels->where('active', 1);
        $availableHotels->with('city');
        $availableHotels->with(['rooms' => function ($query) use ($rooms, $checkOut, $checkIn, $destination) {
            $query->where('active', 1);
            $query->whereDate('start_date', '<=', $checkIn);
            $query->whereDate('end_date', '>=', $checkOut);
            $query->where(function ($q) use ($rooms) {
                foreach ($rooms as $k => $room) {
                    if ($k == 0) {
                        $q->where(function ($query) use ($room) {
                            $query->where('adult_capacity', '>=', $room['adult']);
                            $query->where('child_capacity', '>=', $room['child']);
                            $query->where('infant_capacity', '>=', $room['infant']);
                        });
                    } else {
                        $q->orWhere(function ($query) use ($room) {
                            $query->where('adult_capacity', '>=', $room['adult']);
                            $query->where('child_capacity', '>=', $room['child']);
                            $query->where('infant_capacity', '>=', $room['infant']);
                        });
                    }
                }
            });
            $query->withCount(['bookings as bookings_count' => function ($q) use ($checkIn, $checkOut) {
                $q->where('room_number', 1)
                    ->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($query) use ($checkIn, $checkOut) {
                        $query->where('check_in', '<', $checkIn)
                            ->where('check_out', '>', $checkOut);
                    });
            }]);
            $query->whereDoesntHave('stopSales', function ($q) use ($checkIn, $checkOut) {
                $q->where(function ($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('start_date', [$checkIn, $checkOut])
                        ->orWhereBetween('end_date', [$checkIn, $checkOut])
                        ->orWhere(function ($query) use ($checkIn, $checkOut) {
                            $query->where('start_date', '<', $checkIn)
                                ->where('end_date', '>', $checkOut);
                        });
                });
            });
            $query->havingRaw("units > bookings_count");
            $query->with(['prices', 'room_type']);
        }]);

        $availableHotels = $availableHotels->get()->filter(function ($singleHotel) use ($rooms) {
            $matchingRooms = collect();
            foreach ($rooms as $requestedRoom) {
                $matchingRoom = $singleHotel->rooms->first(function ($room) use ($requestedRoom) {
                    return $room->adult_capacity >= $requestedRoom['adult']
                        && $room->child_capacity >= $requestedRoom['child']
                        && $room->infant_capacity >= $requestedRoom['infant']
                        && ($room->units - $room->bookings_count) > 0;
                });

                if ($matchingRoom) {
                    $matchingRooms->push($matchingRoom);
                }
            }

            // Ensure all requested rooms are found
            return $matchingRooms->count() === count($rooms);
        })->all();

        $hotels = collect();

        foreach ($availableHotels as $availableHotel) {
            // Ensure rooms are unique
            $uniqueRooms = $availableHotel->rooms->unique('room_type_id'); // Distinct all rooms by 'room_type_id'

            // Optionally, reindex the collection to avoid potential issues with non-sequential keys
            $availableHotel->setRelation('rooms', $uniqueRooms->values());

            // Push the hotel with its unique rooms into the collection
            $hotels->push($availableHotel);
        }
        $hotels = $hotels->toArray();
//
//        foreach ($hotels as $h => $hotel) {
//            foreach($hotel['rooms'] as $r => $room) {
//                foreach($room['prices'] as $p => $price) {
//                    if(strtolower($price['currency']) == 'euro') {
//                        $conversionRate = 1;
//
//                        $fields = [
//                            'adult_1', 'adult_2', 'adult_3', 'adult_4', 'adult_5', 'adult_6',
//                            'child_1', 'child_2', 'child_3', 'child_4', 'child_5', 'child_6',
//                            'infant', 'infant_1', 'infant_2', 'infant_3', 'infant_4', 'infant_5', 'infant_6',
//                        ];
//
//                        $settings = Cache::rememberForever('settings', function() {
//                            return \App\Models\Agency::where('id', 1)->first();
//                        });
//
//                        // Handle different possible return types
//                        if (is_object($settings)) {
//                            // Case 1: $settings is a model instance
//                            if($settings instanceof \Illuminate\Support\Collection) {
//                                if (isset($settings->first()->euro) && is_numeric($settings->first()->euro)) {
//                                    $conversionRate = (float)$settings->first()->euro;
//                                }
//                            } else {
//                                if (isset($settings->euro) && is_numeric($settings->euro)) {
//                                    $conversionRate = (float)$settings->euro;
//                                }
//                            }
//                        } elseif (is_array($settings)) {
//                            // Case 2: $settings is an array or collection
//                            if (isset($settings[0]) && isset($settings[0]->euro) && is_numeric($settings[0]->euro)) {
//                                $conversionRate = (float)$settings[0]->euro;
//                            }
//                        }
//                        foreach ($fields as $field) {
//                            if (isset($price[$field]) && is_numeric($price[$field])) {
//                                $hotels[$h]['rooms'][$r]['prices'][$p][$field] = $price[$field] * $conversionRate;
//                            }
//                        }
//                    }
//                }
//            }
//        }

        return $hotels;
    }

    public static function get_hotels_locations($type, $query = [])
    {
        // TODO: Implement get_hotels_locations() method.
    }

    public static function get_hotel_details($id, $params = []): array
    {
        $checkIn = Carbon::parse($params['start_date']);
        $checkOut = Carbon::parse($params['end_date'])->subDay(); // Adjust the check-out date by subtracting one day

        // Fetch hotel details with available rooms
        $hotel = Hotel::query()
            ->where('id', $id)
            ->where('active', 1)
            ->with([
                'rooms' => function($query) use ($checkIn, $checkOut, $params) {
                    $query->with(['room_type', 'features', 'prices']);
                    $query->where('active', 1);
                    $query->whereDate('start_date', '<=', $checkIn)
                        ->whereDate('end_date', '>=', $checkOut)
                        ->withCount(['bookings as bookings_count' => function ($q)  use ($checkIn, $checkOut) {
                            $q->whereBetween('check_in', [$checkIn, $checkOut])
                                ->orWhereBetween('check_out', [$checkIn, $checkOut])
                                ->orWhere(function ($query) use ($checkIn, $checkOut) {
                                    $query->where('check_in', '<', $checkIn)
                                        ->where('check_out', '>', $checkOut);
                                });
                        }])
                        ->havingRaw("rooms.units > bookings_count");
                    // Excluding rooms with stop sales overlapping the check-in/check-out dates
                    $query->whereDoesntHave('stopSales', function ($q) use ($checkIn, $checkOut) {
                        $q->where(function ($query) use ($checkIn, $checkOut) {
                            $query->whereBetween('start_date', [$checkIn, $checkOut])
                                ->orWhereBetween('end_date', [$checkIn, $checkOut])
                                ->orWhere(function ($query) use ($checkIn, $checkOut) {
                                    $query->where('start_date', '<', $checkIn)
                                        ->where('end_date', '>', $checkOut);
                                });
                        });
                    });
            }, 'city', 'features'])
            ->first();

        if (!$hotel) {
            return ['error' => 'Hotel not found or not available for the given dates'];
        }

        $rooms = $hotel->rooms;
        $result = [];
        if(isset($params['rooms']) && count($params['rooms']) > 0) {
            foreach ($params['rooms'] as $index => $singleRequestedRoom) {
                $search = $rooms
                    ->filter(function($room) use ($singleRequestedRoom) {
                        return ($room->adult_capacity >= (int)$singleRequestedRoom['adult']
                                && $room->child_capacity >= (int)$singleRequestedRoom['child']
                                && $room->infant_capacity >= (int)$singleRequestedRoom['infant']
                                && (!is_numeric($room->max_capacity) || $room->max_capacity >= (int)$singleRequestedRoom['adult'] + (int)$singleRequestedRoom['child'])
                        );
                    })
                    ->unique('room_type_id');

                $groupedSearch = ['requested' => $singleRequestedRoom, 'results' => $search->toArray()];

                // Push the results to the final result collection
                $result[] = $groupedSearch;
            }
        }

        $rules = HotelRule::query()->get();

        return [
            'hotel' => $hotel,
            'rooms' => $result,
            'duration' => (int) Carbon::parse($params['start_date'])->diffInDays(Carbon::parse($params['end_date']), true),
            'rules' => $rules,
        ];
    }

    public static function get_hotel_rooms($id, $params = []): array {
        return [];
    }

    public static function getRoomPricing($params = []): array {
        //hotel_id, rooms, start_date, end_date
        $total = 0;
        $room_selection   = $params['room_selection'];

        $roomIds = array_column($room_selection, 'room_id');

        $allRooms = Room::whereIn('id', $roomIds)->with(['prices', 'room_type'])->get();

        foreach($room_selection as $k => $v) {
            $room = $allRooms->where('id', $v['room_id'])->first();
            $price = calculateRoomPrice(
                $room->toArray(),
                $room->prices->toArray(),
                $params['check_in'] ,
                $params['check_out'],
                ['adult' => $v['requested']['adult'] ?? 0, $v['requested']['child'] ?? 0, $v['requested']['infant'] ?? 0]
            );
            $room_selection[$k]['price']      = $price;
            $room_selection[$k]['room_type']  = $room->room_type->name;
            $room_selection[$k]['meal_plan']  = $v['meal_plan'] ?? null;
            $room_selection[$k]['meal_price'] = $v['meal_price'] ?? null;
            $total += $price + (isset($v['meal_price']) ? ($v['meal_price'] * ($v['requested']['adult'] + $v['requested']['child'])) : 0);
        }
        return ['room_selection' => $room_selection ?? [], 'total' => $total ?? 0];
    }
}

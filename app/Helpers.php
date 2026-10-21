<?php

use App\Http\Controllers\Connections\Client\ApiClientSearchController;
use App\Models\Flight;
use App\Models\HotelRule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

if(! function_exists('app_settings')) {
    function app_settings() {
        return Cache::rememberForever('settings', function() {
            return \App\Models\Agency::where('id', 1)->first();
        });
    }
}

if(! function_exists('connections')) {
    function connections() {
        return Cache::rememberForever('connections', function() {
            return \App\Models\Connection::where('active', 1)->get();
        });
    }
}

if(!function_exists('match_flights')) {
}

// app/Helpers.php

if (!function_exists('calculateMarkupPrice')) {
    function calculateMarkupPrice($originalPrice, $airline, $division = 1, $passengers = 1) {
        if(auth()->user()->agency_id == 1) {
            $airline = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $airline));
            $markup = auth()->user()->{$airline.'_markup'};
            if ($division > 1) {
                // If division is greater than 1, divide the markup by the division value
                $markup /= $division;
            }
            if (auth()->user()->{$airline.'_markup_type'} === 'fixed') {
                return $originalPrice + ($markup * $passengers);
            } elseif (auth()->user()->{$airline.'_markup_type'} === 'percentage') {
                $p = $originalPrice * $markup / 100;
                return $originalPrice + ($p * $passengers);
            } else {
                return $originalPrice;
            }
        }
        return $originalPrice;
    }
}

if( ! function_exists('get_agency_id')) {
    function get_agency_id()
    {
        return auth()->user()?->agency_id;
    }
}

if( ! function_exists('get_domain')) {
    function get_domain()
    {
        return preg_replace('/^(https?:\/\/)?(www\.)?/i', '', $_SERVER['SERVER_NAME']);
    }
}

if (! function_exists('calculate_duration')) {
    function calculate_duration($time1, $time2)
    {
        $time1 = \Carbon\Carbon::createFromFormat('H:i:s', $time1);
        $time2 = \Carbon\Carbon::createFromFormat('H:i:s', $time2);

        if ($time2 < $time1) {
            $time2->addDay();
        }
        $diffInMinutes = $time1->diffInMinutes($time2);
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;
        return "$hours:$minutes";
    }
}

if (! function_exists('get_agency_balance')) {
    function get_agency_balance()
    {
        return auth()->user()?->type == 'white-admin' ? \App\Models\Agency::where('id', auth()->user()->agency_id)->select('balance')->first()->balance : 1000000;
    }
}

if (! function_exists('hex2rgba')) {

    function hex2rgba($color, $opacity = false) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if(empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
    }
}

if(!function_exists('getLastPositiveNumber')) {
    function getLastPositiveNumber($string)
    {
        // Remove the first 4 characters
        $numericPart = substr($string, 4);

        // Remove leading zeros only
        $number = ltrim($numericPart, '0');

        return $number === '' ? null : $number; // Return null if empty
    }

}


if(! function_exists('getBusinessPrice')) {
    function getBusinessPrice($object, $type = null, $passengers = [], $package = false): int|array|string
    {
        if(is_array($object)) {
            $object = (object) $object;
            if(is_array($object->prices)) {
                $object->prices = collect((object) $object->prices ?? $object['prices'])->map(function ($price) {
                    return (object) $price;
                });
            }
        }
        $classes = $object->prices->where('type', 'business');
        $tickets = $object->business_tickets_count;
        $available_seats = $classes->sum('seats') - $tickets;
        $needed_seats = 0;
        foreach($passengers as $k => $v) {
            if($k != 'infant') {
                $needed_seats += $v;
            }
        }
        if($available_seats < $needed_seats) {
            return [];
        }
        $sorted = $classes->sortBy('position');
        $prices = [];
        $total = 0;
        $results = [];

        if($type && !count($passengers))
        {
            foreach($sorted->values()->all() as $price):
                $total += $price->seats;
                if($tickets <= $total) {
                    return $price->{$type};
                }
            endforeach;
            if(count($prices) > 0) {
                return $sorted->values()->all()[count($prices) - 1]->{$type};
            } else {
                $sortedValues = $sorted->values()->all();
                return !empty($sortedValues) ? $sortedValues[0]->{$type} : 0;
                //return $sorted->values()->all()[0]->{$type};
            }
        }

        //Remove booked seats
        foreach($sorted->values()->all() as $index => $singlePrice):
            if($tickets >= $singlePrice->seats) {
                $tickets = $tickets - $singlePrice->seats;
                $singlePrice->seats = 0;
                $prices[] = $singlePrice;
            } else {
                $singlePrice->seats = $singlePrice->seats - $tickets;
                $tickets = 0;
                $prices[] = $singlePrice;
            }
        endforeach;

        $sortedPrices = collect($prices)->where('seats', '>', '0')->sortBy('class');

        //For adults
        foreach($sortedPrices as $single):
            if(isset($passengers['adult']) && $passengers['adult'] > 0) {
                if($passengers['adult'] >= $single->seats && $single->seats > 0) {
                    $price = $package ? $single->package_adult : $single->adult;
                    $results['adult'][]  = ['number' => $single->seats, 'price' => $price, 'class' => $single->class, 'id' => $single->id];
                    $passengers['adult'] = $passengers['adult'] - $single->seats;
                    $single->seats = 0;
                } else if($single->seats > 0) {
                    $price = $package ? $single->package_adult : $single->adult;
                    $results['adult'][]  = ['number' => $passengers['adult'], 'price' => $price, 'class' => $single->class, 'id' => $single->id];
                    $single->seats = $single->seats - $passengers['adult'];
                    $passengers['adult'] = 0;
                }
            }
        endforeach;

        //For children
        foreach($sortedPrices as $single):
            if(isset($passengers['child']) && $passengers['child'] > 0) {
                if($passengers['child'] >= $single->seats && $single->seats > 0) {
                    $price = $package ? $single->package_child : $single->child;
                    $results['child'][]  = ['number' => $single->seats, 'price' => $price, 'class' => $single->class, 'id' => $single->id];
                    $passengers['child'] = $passengers['child'] - $single->seats;
                    $single->seats = 0;
                } else if($single->seats > 0) {
                    $price = $package ? $single->package_child : $single->child;
                    $results['child'][]  = ['number' => $passengers['child'], 'price' => $price, 'class' => $single->class, 'id' => $single->id];
                    $passengers['child'] = 0;
                    $single->seats = $single->seats - $passengers['child'];
                }
            }
        endforeach;

        //For infants
        if(isset($passengers['infant']) && $passengers['infant'] > 0) {
            $lastId = end($results);
            if(is_array($lastId)) {
                $lastId = $lastId[count($lastId) - 1]['id'];
                $single = $sortedPrices->where('id', $lastId)->first();
                $price = $package ? $single->package_infant : $single->infant;
                $results['infant'][] = ['number' => $passengers['infant'], 'price' => $price, 'class' => $single->class, 'id' => $single->id];
            }
        }

        if($type && is_array($type)) {
            $r = ['success' => true];
            foreach($type as $key):
                $r[$key] = $results[$key];
            endforeach;
            return $r;
        }
        return $results;
    }
}

if (! function_exists('applyApiPackageRules')) {
    function applyApiPackageRules($hotel_id, $tickets_price, $hotel_price, $total_price, $start_date, $end_date, $passengers, $apiRules = []) {

        if(!is_array($apiRules) || count($apiRules) == 0) {
            return $total_price;
        }

        $allRules = collect($apiRules);

        // Separate hotel-specific and global rules
        $hotelRules = $allRules->filter(function ($rule) use ($hotel_id) {
            return is_array($rule['hotel_ids'] ?? null) && in_array($hotel_id, $rule['hotel_ids']);
        })->values();

        $globalRules = $allRules->filter(function ($rule) {
            return empty($rule['hotel_ids']) || !is_array($rule['hotel_ids']);
        })->values();

        // Convert dates to Carbon for comparison
        $tripStart = $start_date ? \Carbon\Carbon::parse($start_date) : null;
        $tripEnd = $end_date ? \Carbon\Carbon::parse($end_date) : null;
        $tripDuration = ($tripStart && $tripEnd) ? $tripStart->diffInDays($tripEnd) + 1 : null; // inclusive

        $appliedRule = null;
        $finalTicketsPrice = $tickets_price;
        $finalHotelPrice = $hotel_price;
        $finalTotalPrice = $total_price;

        // Helper to check all conditions for a rule
        $ruleMatches = function($rule) use ($tripStart, $tripEnd, $tripDuration) {
            $conditions = [];

            // Date restriction
            if ($rule['start_date'] || $rule['end_date']) {
                $dateMatch = true;
                $ruleStart = $rule['start_date'] ? \Carbon\Carbon::parse($rule['start_date']) : null;
                $ruleEnd = $rule['end_date'] ? \Carbon\Carbon::parse($rule['end_date']) : null;
                if ($ruleStart && $ruleEnd && $tripStart && $tripEnd) {
                    $dateMatch = $tripStart->between($ruleStart, $ruleEnd) && $tripEnd->between($ruleStart, $ruleEnd);
                } elseif ($ruleStart && $tripStart) {
                    $dateMatch = $tripStart->greaterThanOrEqualTo($ruleStart);
                } elseif ($ruleEnd && $tripEnd) {
                    $dateMatch = $tripEnd->lessThanOrEqualTo($ruleEnd);
                }
                $conditions[] = $dateMatch;
            }

            // Duration restriction
            if (!empty($rule['duration_type'])) {
                if ($rule['duration'] !== null && $rule['duration'] !== '' && $rule['duration'] != 'any') {
                    if ($rule['duration_type'] == 'less_than') {
                        $conditions[] = ($tripDuration < (int)$rule['duration']);
                    } elseif ($rule['duration_type'] === 'greater_than') {
                        $conditions[] = ($tripDuration > (int)$rule['duration']);
                    } else { // equal or any other value
                        $conditions[] = ($tripDuration === (int)$rule['duration']);
                    }
                }
            }


            // Add more conditions here if your rules have other restrictions

            // If no conditions are set, it's a match
            if (empty($conditions)) return true;
            // Otherwise, all set conditions must be true
            return !in_array(false, $conditions, true);
        };

        // 1. Check hotel-specific rules first
        foreach ($hotelRules as $rule) {
            if ($ruleMatches($rule)) {
                // Use sub_rules_json (array)
                $subRules = $rule['sub_rules_json'] ?? [];
                if (is_string($subRules)) {
                    $subRules = json_decode($subRules, true) ?: [];
                }

                foreach ($subRules as $subRule) {
                    $amountType = $subRule['amount_type'] ?? 'percentage';
                    $amount = $subRule['amount'] ?? 0;
                    $applicableTo = $subRule['applicable_to'] ?? 'total';
                    $perPerson = isset($subRule['per_person']) && $subRule['per_person'];

                    if ($applicableTo === 'hotel') {
                        $targetValue = $finalHotelPrice;
                    } elseif ($applicableTo === 'ticket') {
                        $targetValue = $finalTicketsPrice;
                    } else {
                        $targetValue = $finalTotalPrice;
                    }
                    if ($amountType === 'percentage') {
                        $add = $targetValue * ($amount / 100);
                        if ($applicableTo === 'hotel') {
                            $finalHotelPrice += $add;
                            $finalTotalPrice += $add;
                        } elseif ($applicableTo === 'ticket') {
                            $finalTicketsPrice += $add;
                            $finalTotalPrice += $add;
                        } else {
                            $finalTotalPrice += $add;
                        }
                    } elseif ($amountType === 'fixed') {
                        $fixedAdd = $amount;
                        if ($perPerson) {
                            $fixedAdd *= $passengers;
                        }
                        if ($applicableTo === 'hotel') {
                            $finalHotelPrice += $fixedAdd;
                            $finalTotalPrice += $fixedAdd;
                        } elseif ($applicableTo === 'ticket') {
                            $finalTicketsPrice += $fixedAdd;
                            $finalTotalPrice += $fixedAdd;
                        } else {
                            $finalTotalPrice += $fixedAdd;
                        }
                    }
                }
                $appliedRule = $rule;
                break;
            }
        }

        // 2. If no hotel-specific rule matched, check global rules
        if (!$appliedRule) {
            foreach ($globalRules as $rule) {

                if ($ruleMatches($rule)) {
                    // Use sub_rules_json (array)
                    $subRules = $rule['sub_rules_json'] ?? [];

                    if (is_string($subRules)) {
                        $subRules = json_decode($subRules, true) ?: [];
                    }

                    foreach ($subRules as $subRule) {
                        $amountType = $subRule['amount_type'] ?? 'percentage';
                        $amount = $subRule['amount'] ?? 0;
                        $applicableTo = $subRule['applicable_to'] ?? 'total';
                        $perPerson = isset($subRule['per_person']) && $subRule['per_person'];

                        if ($applicableTo === 'hotel') {
                            $targetValue = $finalHotelPrice;
                        } elseif ($applicableTo === 'ticket') {
                            $targetValue = $finalTicketsPrice;
                        } else {
                            $targetValue = $finalTotalPrice;
                        }

                        if ($amountType === 'percentage') {
                            $add = $targetValue * ($amount / 100);
                            if ($applicableTo === 'hotel') {
                                $finalHotelPrice += $add;
                                $finalTotalPrice += $add;
                            } elseif ($applicableTo === 'ticket') {
                                $finalTicketsPrice += $add;
                                $finalTotalPrice += $add;
                            } else {
                                $finalTotalPrice += $add;
                            }
                        } elseif ($amountType === 'fixed') {
                            $fixedAdd = $amount;
                            if ($perPerson) {
                                $fixedAdd *= $passengers;
                            }
                            if ($applicableTo === 'hotel') {
                                $finalHotelPrice += $fixedAdd;
                                $finalTotalPrice += $fixedAdd;
                            } elseif ($applicableTo === 'ticket') {
                                $finalTicketsPrice += $fixedAdd;
                                $finalTotalPrice += $fixedAdd;
                            } else {
                                $finalTotalPrice += $fixedAdd;
                            }
                        }
                    }
                    $appliedRule = $rule;
                    break;
                }
            }
        }
        // Return both the adjusted price and the rule that was applied (if any)
        return $finalTotalPrice;
    }
}

if (! function_exists('applyPackageRules')) {
    function applyPackageRules($hotel_id, $tickets_price, $hotel_price, $total_price, $start_date, $end_date, $passengers = 0) {

        $allRules = \App\Models\HotelRule::query()
            ->orderBy('position')
            ->where('status', 1)
            ->get();

        // Separate hotel-specific and global rules
        $hotelRules = $allRules->filter(function ($rule) use ($hotel_id) {
            return is_array($rule['hotel_ids'] ?? null) && in_array($hotel_id, $rule['hotel_ids']);
        })->values();

        $globalRules = $allRules->filter(function ($rule) {
            return empty($rule['hotel_ids']) || !is_array($rule['hotel_ids']);
        })->values();

        // Convert dates to Carbon for comparison
        $tripStart = $start_date ? \Carbon\Carbon::parse($start_date)->startOfDay() : null;
        $tripEnd = $end_date ? \Carbon\Carbon::parse($end_date)->startOfDay() : null;

        $tripDuration = ($tripStart && $tripEnd) ? $tripStart->diffInDays($tripEnd) + 1 : null; // inclusive

        $appliedRule = null;
        $finalTicketsPrice = $tickets_price;
        $finalHotelPrice = $hotel_price;
        $finalTotalPrice = $total_price;

        // Helper to check all conditions for a rule
        $ruleMatches = function($rule) use ($tripStart, $tripEnd, $tripDuration) {
            $conditions = [];

            // Date restriction

            if ($rule->start_date || $rule->end_date) {
                $dateMatch = true;
                $ruleStart = $rule->start_date ? \Carbon\Carbon::parse($rule->start_date)->startOfDay() : null;
                $ruleEnd = $rule->end_date ? \Carbon\Carbon::parse($rule->end_date)->startOfDay() : null;
                if ($ruleStart && $ruleEnd && $tripStart && $tripEnd) {
                    $dateMatch = $tripStart->between($ruleStart, $ruleEnd);
                } elseif ($ruleStart && $tripStart) {
                    $dateMatch = $tripStart->greaterThanOrEqualTo($ruleStart);
                } elseif ($ruleEnd && $tripEnd) {
                    $dateMatch = $tripEnd->lessThanOrEqualTo($ruleEnd);
                }
                $conditions[] = $dateMatch;
            }

            // Duration restriction
            if (!empty($rule->duration_type)) {
                if ($rule->duration !== null && $rule->duration !== '' && $rule->duration != 'any') {
                    if ($rule->duration_type === 'less_than') {
                        $conditions[] = ($tripDuration < (int)$rule->duration);
                    } elseif ($rule->duration_type === 'greater_than') {
                        $conditions[] = ($tripDuration > (int)$rule->duration);
                    } else { // equal or any other value
                        $conditions[] = ($tripDuration === (int)$rule->duration);
                    }
                }
            }


            // Add more conditions here if your rules have other restrictions

            // If no conditions are set, it's a match
            if (empty($conditions)) return true;
            // Otherwise, all set conditions must be true
            return !in_array(false, $conditions, true);
        };

        // 1. Check hotel-specific rules first
        foreach ($hotelRules as $rule) {
            if ($ruleMatches($rule)) {
                // Use sub_rules_json (array)
                $subRules = $rule->sub_rules_json ?? [];
                if (is_string($subRules)) {
                    $subRules = json_decode($subRules, true) ?: [];
                }

                foreach ($subRules as $subRule) {
                    $amountType = $subRule['amount_type'] ?? 'percentage';
                    $amount = $subRule['amount'] ?? 0;
                    $applicableTo = $subRule['applicable_to'] ?? 'total';
                    $perPerson = isset($subRule['per_person']) && $subRule['per_person'];

                    if ($applicableTo === 'hotel') {
                        $targetValue = $finalHotelPrice;
                    } elseif ($applicableTo === 'ticket') {
                        $targetValue = $finalTicketsPrice;
                    } else {
                        $targetValue = $finalTotalPrice;
                    }

                    if ($amountType === 'percentage') {
                        $add = $targetValue * ($amount / 100);
                        if ($applicableTo === 'hotel') {
                            $finalHotelPrice += $add;
                            $finalTotalPrice += $add;
                        } elseif ($applicableTo === 'ticket') {
                            $finalTicketsPrice += $add;
                            $finalTotalPrice += $add;
                        } else {
                            $finalTotalPrice += $add;
                        }
                    } elseif ($amountType === 'fixed') {
                        $fixedAdd = $amount;
                        if ($perPerson) {
                            $fixedAdd *= $passengers;
                        }
                        if ($applicableTo === 'hotel') {
                            $finalHotelPrice += $fixedAdd;
                            $finalTotalPrice += $fixedAdd;
                        } elseif ($applicableTo === 'ticket') {
                            $finalTicketsPrice += $fixedAdd;
                            $finalTotalPrice += $fixedAdd;
                        } else {
                            $finalTotalPrice += $fixedAdd;
                        }
                    }
                }
                $appliedRule = $rule;
                break;
            }
        }

        // 2. If no hotel-specific rule matched, check global rules
        if (!$appliedRule) {
            foreach ($globalRules as $rule) {
                if ($ruleMatches($rule)) {
                    // Use sub_rules_json (array)
                    $subRules = $rule->sub_rules_json ?? [];
                    if (is_string($subRules)) {
                        $subRules = json_decode($subRules, true) ?: [];
                    }

                    foreach ($subRules as $subRule) {
                        $amountType = $subRule['amount_type'] ?? 'percentage';
                        $amount = $subRule['amount'] ?? 0;
                        $applicableTo = $subRule['applicable_to'] ?? 'total';
                        $perPerson = isset($subRule['per_person']) && $subRule['per_person'];

                        if ($applicableTo === 'hotel') {
                            $targetValue = $finalHotelPrice;
                        } elseif ($applicableTo === 'ticket') {
                            $targetValue = $finalTicketsPrice;
                        } else {
                            $targetValue = $finalTotalPrice;
                        }

                        if ($amountType === 'percentage') {
                            $add = $targetValue * ($amount / 100);
                            if ($applicableTo === 'hotel') {
                                $finalHotelPrice += $add;
                                $finalTotalPrice += $add;
                            } elseif ($applicableTo === 'ticket') {
                                $finalTicketsPrice += $add;
                                $finalTotalPrice += $add;
                            } else {
                                $finalTotalPrice += $add;
                            }
                        } elseif ($amountType === 'fixed') {
                            $fixedAdd = $amount;
                            if ($perPerson) {
                                $fixedAdd *= $passengers;
                            }
                            if ($applicableTo === 'hotel') {
                                $finalHotelPrice += $fixedAdd;
                                $finalTotalPrice += $fixedAdd;
                            } elseif ($applicableTo === 'ticket') {
                                $finalTicketsPrice += $fixedAdd;
                                $finalTotalPrice += $fixedAdd;
                            } else {
                                $finalTotalPrice += $fixedAdd;
                            }
                        }
                    }
                    $appliedRule = $rule;
                    break;
                }
            }
        }
        // Return both the adjusted price and the rule that was applied (if any)
        return $finalTotalPrice;
    }
}

if(! function_exists('getPrice')) {

    function getPrice($object, $type = null, $passengers = [], $package = false): int|array|string
    {
        if(is_array($object)) {
            $object = (object) $object;
            if(is_array($object->prices)) {
                $object->prices = collect((object) $object->prices ?? $object['prices'])->map(function ($price) {
                    return (object) $price;
                });
            }
        }

        $classes = $object->prices->where('type', 'economy');

        $tickets = ($object->tickets_count - $object->business_tickets_count ?? 0);
        $sorted = $classes->sortBy('position');
        $prices = [];
        $total = 0;
        $results = [];

        $available_seats = $classes->sum('seats') - $tickets;
        $needed_seats = 0;
        foreach($passengers as $k => $v) {
            if($k != 'infant') {
                $needed_seats += $v;
            }
        }
        if($available_seats < $needed_seats) {
            return [];
        }

        if($type && !count($passengers))
        {
            foreach($sorted->values()->all() as $price):
                $total += $price->seats;
                if($tickets <= $total) {
                    return $price->{$type};
                }
            endforeach;
            if(count($prices) > 0) {
                return $sorted->values()->all()[count($prices) - 1]->{$type};
            } else {
                return $sorted->values()->all()[0]->{$type};
            }
        }

        //Remove booked seats
        foreach($sorted->values()->all() as $index => $singlePrice):
            if($tickets >= $singlePrice->seats) {
                $tickets = $tickets - $singlePrice->seats;
                $singlePrice->seats = 0;
                $prices[] = $singlePrice;
            } else {
                $singlePrice->seats = $singlePrice->seats - $tickets;
                $tickets = 0;
                $prices[] = $singlePrice;
            }
        endforeach;


        $sortedPrices = collect($prices)->where('seats', '>', '0')->sortBy('position');

        //For adults
        foreach($sortedPrices as $single):
            if(isset($passengers['adult']) && $passengers['adult'] > 0) {
                if($passengers['adult'] >= $single->seats && $single->seats > 0) {
                    $price = $package ? $single->package_adult : $single->adult;
                    $results['adult'][]  = ['number' => $single->seats, 'price' => $price, 'class' => $single->class, 'id' => $single->id];
                    $passengers['adult'] = $passengers['adult'] - $single->seats;
                    $single->seats = 0;
                } else if($single->seats > 0) {
                    $price = $package ? $single->package_adult : $single->adult;
                    $results['adult'][]  = ['number' => $passengers['adult'], 'price' => $price, 'class' => $single->class, 'id' => $single->id];
                    $single->seats = $single->seats - $passengers['adult'];
                    $passengers['adult'] = 0;
                }
            }
        endforeach;

        //For children
        foreach($sortedPrices as $single):
            if(isset($passengers['child']) && $passengers['child'] > 0) {
                if($passengers['child'] >= $single->seats && $single->seats > 0) {
                    $price = $package ? $single->package_child : $single->child;
                    $results['child'][]  = ['number' => $single->seats, 'price' => $price, 'class' => $single->class, 'id' => $single->id];
                    $passengers['child'] = $passengers['child'] - $single->seats;
                    $single->seats = 0;
                } else if($single->seats > 0) {
                    $price = $package ? $single->package_child : $single->child;
                    $results['child'][]  = ['number' => $passengers['child'], 'price' => $price, 'class' => $single->class, 'id' => $single->id];
                    $passengers['child'] = 0;
                    $single->seats = $single->seats - $passengers['child'];
                }
            }
        endforeach;

        //For infants
        if(isset($passengers['infant']) && $passengers['infant'] > 0) {
            $lastId = end($results);
            $lastId = $lastId[count($lastId) - 1]['id'];
            $single = $sortedPrices->where('id', $lastId)->first();
            $price = $package ? $single->package_infant : $single->infant;
            $results['infant'][] = ['number' => $passengers['infant'], 'price' => $price, 'class' => $single->class, 'id' => $single->id];
        }

        if($type && is_array($type)) {
            $r = ['success' => true];
            foreach($type as $key):
                $r[$key] = $results[$key];
            endforeach;
            return $r;
        }
        return $results;
    }
}

if( ! function_exists('countries')) {
    function countries(): array
    {
        return array
        (
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua And Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia And Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island & Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic Of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle Of Man',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States Of',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts And Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre And Miquelon',
            'VC' => 'Saint Vincent And Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome And Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia And Sandwich Isl.',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard And Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad And Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks And Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis And Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );
    }
}

if (! function_exists('numberToWords')) {
    function numberToWords($num = '', $seq = false)
    {
        $num    = ( string ) ( ( int ) $num );

        if($seq) {
            $ordinalWords = array(
                1 => 'First',
                2 => 'Second',
                3 => 'Third',
                4 => 'Fourth',
                5 => 'Fifth',
                6 => 'Sixth',
                7 => 'Seventh',
                8 => 'Eighth',
                9 => 'Ninth',
                10 => 'Tenth'
            );
            if (array_key_exists($num, $ordinalWords)) {
                return $ordinalWords[$num];
            } else {
                return '';
            }
        } else {
            if( ( int ) ( $num ) && ctype_digit( $num ) )
            {
                $words  = array( );

                $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );

                $list1  = array('','one','two','three','four','five','six','seven',
                    'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                    'fifteen','sixteen','seventeen','eighteen','nineteen');

                $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                    'seventy','eighty','ninety','hundred');

                $list3  = array('','thousand','million','billion','trillion',
                    'quadrillion','quintillion','sextillion','septillion',
                    'octillion','nonillion','decillion','undecillion',
                    'duodecillion','tredecillion','quattuordecillion',
                    'quindecillion','sexdecillion','septendecillion',
                    'octodecillion','novemdecillion','vigintillion');

                $num_length = strlen( $num );
                $levels = ( int ) ( ( $num_length + 2 ) / 3 );
                $max_length = $levels * 3;
                $num    = substr( '00'.$num , -$max_length );
                $num_levels = str_split( $num , 3 );

                foreach( $num_levels as $num_part )
                {
                    $levels--;
                    $hundreds   = ( int ) ( $num_part / 100 );
                    $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                    $tens       = ( int ) ( $num_part % 100 );
                    $singles    = '';

                    if( $tens < 20 ) { $tens = ( $tens ? ' ' . $list1[$tens] . ' ' : '' ); } else { $tens = ( int ) ( $tens / 10 ); $tens = ' ' . $list2[$tens] . ' '; $singles = ( int ) ( $num_part % 10 ); $singles = ' ' . $list1[$singles] . ' '; } $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' ); } $commas = count( $words ); if( $commas > 1 )
            {
                $commas = $commas - 1;
            }

                $words  = implode( ', ' , $words );

                $words  = trim( str_replace( ' ,' , ',' , ucwords( $words ) )  , ', ' );
                if( $commas )
                {
                    $words  = str_replace( ',' , ' and' , $words );
                }

                return $words;
            }
            else if( ! ( ( int ) $num ) )
            {
                return 'Zero';
            }
        }
        return '';
    }
}

if (! function_exists('calculateRoomPrice') ) {
    function calculateRoomPrice($room, $prices, string $startDate, string $endDate, $params = ['adult' => 0, 'child' => 0, 'infant' => 0], bool $separated = false)
    {
        $totalPrice = 0.0; // Initialize with float for precision
        $separatedPrices = []; // Initialize an array to store separated prices if needed

        // Pricing types: each, total, fixed
        $adultPricingType   = $room['adult_pricing'];
        $childPricingType   = $room['child_pricing'];
        $infantPricingType  = $room['infant_pricing'];

        $numberOfAdults     = $params['adult'] ?? 0;
        $numberOfChildren   = $params['child'] ?? 0;
        $numberOfInfants    = $params['infant'] ?? 0;

        // Ensure Carbon instances for date calculations
        $startDate  = Carbon::parse($startDate);
        $endDate    = Carbon::parse($endDate)->subDay();

        foreach ($prices as $price) {
            // Extract price details (assuming consistent key names)
            $priceStartDate = Carbon::parse($price['start_date']);
            $priceEndDate   = Carbon::parse($price['end_date']);

            // Calculate overlapping date range (handle edge cases)
            $overlappingStartDate = $startDate->max($priceStartDate);
            $overlappingEndDate = $endDate->min($priceEndDate);

            // Check for valid overlap and handle single-day booking case
            if ($overlappingStartDate->lte($overlappingEndDate)) {
                $daysInPriceRange = $overlappingEndDate->diffInDays($overlappingStartDate) + 1;

                // Calculate price per day based on pricing type
                $pricePerDay = calculatePricePerDay($price, $adultPricingType, $childPricingType, $infantPricingType, $numberOfAdults, $numberOfChildren, $numberOfInfants);

                // Calculate total price for this price range
                $calculatedPrice = $daysInPriceRange * $pricePerDay;

                // Accumulate total price or store separated prices
                if ($separated) {
                    $separatedPrices[] = $calculatedPrice;
                } else {
                    $totalPrice += $calculatedPrice;
                }
            }
        }

        return $separated ? $separatedPrices : $totalPrice;
    }

}

if(!function_exists('calculatePricePerDay')) {
    function calculatePricePerDay($price, $adultPricingType, $childPricingType, $infantPricingType, $numberOfAdults, $numberOfChildren, $numberOfInfants)
    {
        $pricePerDay = 0.0;

        // Handle adult pricing
        if ($adultPricingType == 'each') {
            for ($i = 1; $i <= $numberOfAdults; $i++) {
                $pricePerDay += $price["adult_$i"] ?? 0;
            }
        } elseif ($adultPricingType == 'total') {
            $pricePerDay += $price["adult_{$numberOfAdults}"] ?? 0;
        } elseif ($adultPricingType == 'fixed') {
            $pricePerDay += $numberOfAdults * ($price['adult_1'] ?? 0);
        }

        // Handle child pricing
        if ($childPricingType == 'each') {
            for ($i = 1; $i <= $numberOfChildren; $i++) {
                $pricePerDay += $price["child_$i"] ?? 0;
            }
        } elseif ($childPricingType == 'total') {
            $pricePerDay += $price["child_{$numberOfChildren}"] ?? 0;
        } elseif ($childPricingType == 'fixed') {
            $pricePerDay += $numberOfChildren * ($price['child_1'] ?? 0);
        }

        // Handle infant pricing
        if ($infantPricingType == 'each') {
            for ($i = 1; $i <= $numberOfInfants; $i++) {
                $pricePerDay += $price["infant_$i"] ?? 0;
            }
        } elseif ($infantPricingType == 'total') {
            $pricePerDay += $price["infant_{$numberOfInfants}"] ?? 0;
        } elseif ($infantPricingType == 'fixed') {
            $pricePerDay += $numberOfInfants * ($price['infant_1'] ?? 0);
        }

        return $pricePerDay;
    }
}

if(!function_exists('getSearchData')) {

    function getSearchData()
    {
        \Log::info('getSearchData function called');

        if (!Cache::has('search')) {
            \Log::info('Search cache miss, regenerating data');

            // Get flights data and log results
            $flights = Flight::searching()->get();
            \Log::info('Flights query results:', [
                'count' => $flights->count(),
                'data' => $flights->toArray()
            ]);

            $flights->each(function ($flight) {
                $flight->is_connection = false;
            });

            // Get API data and log results
            $apiData = ApiClientSearchController::getSearchLocations();
            \Log::info('API data results:', [
                'type' => gettype($apiData),
                'count' => is_array($apiData) ? count($apiData) : 'not countable',
                'data' => $apiData
            ]);

            $locations = array_merge($flights->toArray(), $apiData ?? []);
            \Log::info('Merged locations:', [
                'count' => count($locations),
                'sample' => array_slice($locations, 0, 3) // Log first 3 items as sample
            ]);

            Cache::put('search', collect($locations), now()->addMinutes(30));
            \Log::info('Data cached successfully');
        } else {
            \Log::info('Using cached search data');
        }

        $result = Cache::get('search');
        \Log::info('Returning search data:', [
            'type' => get_class($result),
            'count' => $result->count()
        ]);

        return $result;
    }
}

if(!function_exists('arrayToObject')) {
    function arrayToObject($array) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // Check if the array is an array of arrays
                if (count($value) > 0 && is_array(current($value))) {
                    // Convert each nested array to an object
                    $array[$key] = collect(array_map(function($item) {
                        return arrayToObject($item);
                    }, $value));
                } else {
                    // Convert the array to an object
                    $array[$key] = arrayToObject($value);
                }
            }
        }
        return (object) $array;
    }
}

function getSurroundingDates(Collection $collection, $selectedDate, $range = 5)
{
    // Parse the selected date
    $selectedDate = Carbon::createFromFormat('d-m-Y', $selectedDate);

    // Sort the collection by dep_date and get unique dates
    $dates = $collection->pluck('dep_date')->unique()->sort(function ($a, $b) {
        return Carbon::createFromFormat('d-m-Y', $a)->lt(Carbon::createFromFormat('d-m-Y', $b)) ? -1 : 1;
    });

    // Find the index of the selected date
    $index = $dates->search($selectedDate->format('d-m-Y'));

    if ($index === false) {
        // Handle the case where the selected date is not found
        return null;
    }

    // Get the dates before and after the selected date
    $before = $dates->slice(max(0, $index - $range), $range);
    $after = $dates->slice($index + 1, $range);

    return $before->merge([$selectedDate->format('d-m-Y')])->merge($after);
}

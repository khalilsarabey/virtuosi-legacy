<?php

namespace App\Livewire\Frontend;

use App\Models\Pricing;
use App\Models\PricingAirline;
use App\Models\PricingAnnouncement;
use App\Models\PricingCategory;
use App\Models\PricingClass;
use App\Models\PricingClassDetail;
use App\Models\TurkishAirport;
use App\Models\TurkishCustomPricing;
use App\Models\TurkishDestination;
use App\Models\TurkishSection;
use App\Models\User;
use Livewire\Component;

class AutoPricing extends Component
{
    public $pricing = [];
    public $airlines;
    public $airports;
    public $selectedCategory;
    public $selectedAirline;
    public $selectedAgency;
    public $destination;
    public $selectedAirport;
    public $selectedAirportClass;
    public $airline;
    public $agencies;
    public $base_fare;
    public $total_fare;
    public $selling_fare;
    public $net_fare;

    public $agencyObject;
    public $categories;
    /**
     * @var mixed[]
     */
    public array $airportClasses;
    /**
     * @var mixed[]
     */
    public array $customClassesValues = [];
    public bool $isCustom;
    public $airportClassesObjects;

    public $announcements = [];
    public $announcement;
    public $turkish_sections;
    public bool $is_turkish;

    public function mount()
    {
        if(auth()->user()->auto_pricing != 1):
            return redirect()->route('frontend.home');
        endif;
        if(auth()->user()->type == 'admin' || auth()->user()->type == 'user') {
            $this->selectedAgency = null;
            $this->agencies = User::where('type', 'agency')->orderBy('name')->get();
        } else {
            $this->selectedAgency = auth()->user()->id;
            $this->agencyObject = auth()->user();
        }
        $this->turkish_sections = TurkishSection::orderBy('title')->where('active', 1)->orderBy('position')->get();
        $this->categories = PricingCategory::orderBy('name')->get();
    }

    public function render()
    {
        if(auth()->user()->type == 'admin' || auth()->user()->type == 'user') {
            $this->announcement = PricingAnnouncement::query()
                ->where('internal', 1)
                ->where(function($q) {
                    $q->where('viewed_by', 'NOT LIKE', '%'.auth()->user()->email.'%')
                        ->orWhere('viewed_by', '')
                        ->orWhereNull('viewed_by');
                })
                ->get();
        }
        if(auth()->user()->type == 'agency') {
            $this->announcement = PricingAnnouncement::query()
                ->where('agencies', 1)
                ->where(function($q) {
                    $q->where('viewed_by', 'NOT LIKE', '%'.auth()->user()->email.'%')
                        ->orWhere('viewed_by', '')
                        ->orWhereNull('viewed_by');
                })
                ->get();
        }
        return view('livewire.frontend.auto-pricing');
    }

    public function closeAnnouncement($id)
    {
        $announcement = PricingAnnouncement::find($id);
        $views = $announcement->viewed_by ?? [];
        $views[] = auth()->user()->email;
        $announcement->update([
            'viewed_by' => $views
        ]);
    }

    public function resetData()
    {
        $this->base_fare = null;
        $this->total_fare = null;
        $this->selling_fare = null;
        $this->net_fare = null;
        $this->selectedAgency = null;
        $this->populate();
    }

    public function updatedSelectedCategory($k)
    {
        if(\Illuminate\Support\Str::startsWith($k, 'turkish-')) {
            $id = str_replace('turkish-', '', $k);
            $this->airports = TurkishAirport::where('turkish_section_id', $id)->orderBy('code')->get();
            $this->selectedAirport = null;
            $this->selectedAirline = null;
            $this->selectedAirportClass = null;
            $this->destination = null;
            $this->is_turkish = true;
        } else {
            $this->airlines = PricingAirline::where('pricing_category_id', $this->selectedCategory)->orderBy('name')->get();
            $this->selectedAirline = null;
            $this->selectedAirport = null;
            $this->selectedAirportClass = null;
            $this->destination = null;
            $this->is_turkish = false;
            $this->populate();
        }
    }

    public function updatedSelectedAirport()
    {
        $this->selectedAirportClass = null;
        $this->destination = null;
        $airport = TurkishAirport::where('id', $this->selectedAirport)->first();
        $this->destination = TurkishDestination::where('id', $airport->turkish_destination_id)->first();
        $custom = \App\Models\TurkishCustomPricing::where([
            //'turkish_airport_id' => $airport->id,
            'user_id' => (int) $this->selectedAgency,
            'turkish_destination_id' => $this->destination->id
        ])->first();
        if($custom) {
            $this->isCustom = true;
            $this->airportClassesObjects = $custom->classes()->get();
            $this->airportClasses = $this->airportClassesObjects->pluck('name', 'id')->toArray();
            $this->pricing['agency_remark']         = $custom->agency_remark;
            $this->pricing['fare_remark']           = $custom->fare_remark;
        } else {
            $this->isCustom = false;
            $this->airportClassesObjects = $this->destination->classes()->get();
            $this->airportClasses = $this->airportClassesObjects->pluck('name', 'id')->toArray();
            $this->pricing['agency_remark']         = $this->destination->agency_remark;
            $this->pricing['fare_remark']           = $this->destination->fare_remark;
        }
    }

    public function updatedSelectedAirportClass()
    {
        $class = $this->airportClassesObjects->where('id', $this->selectedAirportClass)->first();
        $this->pricing['selling_commission']    = $class->selling_commission;
        $this->pricing['selling_service_fee']   = $class->selling_service_fee;
        $this->pricing['selling_fixed_fee']     = $class->selling_fixed_fee;
        $this->pricing['net_commission']        = $class->net_commission;
        $this->pricing['net_service_fee']       = $class->net_service_fee;
    }

    public function updatedSelectedAirline()
    {
        $this->airline = PricingAirline::where('id', $this->selectedAirline)->first();
        $this->populate();
    }

    public function updatedSelectedAgency()
    {
        $this->agencyObject = User::where('id', $this->selectedAgency)->select(['id', 'name', 'pricing_class_id', 'notes'])->first();
        if($this->agencyObject) {
            $this->populate();
        }
    }

    public function populateTurkish()
    {

    }

    public function populate()
    {
        if($this->selectedAirline && $this->selectedAgency && $this->selectedCategory) {
            $pricing = Pricing::where([
                'user_id' => $this->selectedAgency,
                'pricing_airline_id' => $this->selectedAirline
            ])->first();
            if($pricing) {
                $this->pricing = [
                    'pricing_id'            => $pricing->id,
                    'selling_commission'    => $pricing->selling_commission,
                    'selling_service_fee'   => $pricing->selling_service_fee,
                    'selling_fixed_fee'     => $pricing->selling_fixed_fee,
                    'net_commission'        => $pricing->net_commission,
                    'net_service_fee'       => $pricing->net_service_fee,
                    'agency_remark'         => $pricing->agency_remark,
                    'fare_remark'           => $pricing->fare_remark,
                ];
            } else {
                $class = PricingClassDetail::where([
                    'pricing_class_id'      => $this->agencyObject->pricing_class_id,
                    'pricing_airline_id'    => $this->selectedAirline
                ])->first();
                if($class) {
                    $this->pricing['selling_commission'] = $class->selling_commission;
                    $this->pricing['selling_service_fee'] = $class->selling_service_fee;
                    $this->pricing['selling_fixed_fee'] = $class->selling_fixed_fee;
                    $this->pricing['net_commission'] = $class->net_commission;
                    $this->pricing['net_service_fee'] = $class->net_service_fee;
                    $this->pricing['agency_remark'] = $this->airline->agency_remark;
                    $this->pricing['fare_remark'] = $this->airline->fare_remark;
                } else {
                    $price = Pricing::where([
                        'user_id' => $this->selectedAgency,
                        'pricing_airline_id' => $this->selectedAirline
                    ])->first();
                    if($price) {
                        $this->pricing['selling_commission'] = $price->selling_commission;
                        $this->pricing['selling_service_fee'] = $price->selling_service_fee;
                        $this->pricing['selling_fixed_fee'] = $price->selling_fixed_fee;
                        $this->pricing['net_commission'] = $price->net_commission;
                        $this->pricing['net_service_fee'] = $price->net_service_fee;
                        $this->pricing['agency_remark'] = $price->agency_remark;
                        $this->pricing['fare_remark'] = $price->fare_remark;
                    } else {
                        $this->pricing['selling_commission'] = $this->airline->default_selling_commission;
                        $this->pricing['selling_service_fee'] = $this->airline->default_selling_service_fee;
                        $this->pricing['selling_fixed_fee'] = $this->airline->default_selling_fixed_fee;
                        $this->pricing['net_commission'] = $this->airline->default_net_commission;
                        $this->pricing['net_service_fee'] = $this->airline->default_net_service_fee;
                        $this->pricing['agency_remark'] = $this->airline->agency_remark;
                        $this->pricing['fare_remark'] = $this->airline->fare_remark;
                    }
                }
            }
        }
    }

    public function calculate()
    {
//          $sub = base fare - commission % + (total fare - base fare)
//          400 - 44 = 356 + 100 = 456 + service fee percentage
//          $total = $sub + service_fee
        if($this->base_fare && $this->total_fare && $this->base_fare > 0 && $this->total_fare > 0) {
            $selling_commission = $this->base_fare * ($this->pricing['selling_commission'] /100);
            $v1 = $this->base_fare - $selling_commission + ($this->total_fare - $this->base_fare);
            $service_fee = $v1 * ($this->pricing['selling_service_fee'] / 100);
            $selling_price = $v1 + $service_fee;
            $this->selling_fare = $selling_price + $this->pricing['selling_fixed_fee'];
            if(auth()->user()->type == 'admin' || auth()->user()->type == 'user') {
                $net_commission = $this->base_fare * ($this->pricing['net_commission'] /100);
                $v2 = $this->base_fare - $net_commission + ($this->total_fare - $this->base_fare);
                $service_fee = $v2 * ($this->pricing['net_service_fee'] / 100);
                $net_price = $v2 + $service_fee;
                $this->net_fare = $net_price;
            }
        }
    }
}

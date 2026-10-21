<?php

namespace App\Livewire\Admin\Hotels;

use App\Models\BookingRoom;
use App\Models\Feature;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\RoomType;
use App\Models\StopSale;
use App\Traits\UploadTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EditRoom extends Component
{
    use UploadTrait;
    public $rule = [
        'adult'     => [
            'capacity' => 1,
            'pricing' => 'fixed',
            'extra_beds' => null
        ],
        'child'     => [
            'capacity' => 1,
            'pricing' => 'fixed',
            'extra_beds' => null
        ],
        'infant'    => [
            'capacity' => 1,
            'pricing' => 'free'
        ],
    ];
    public int $units = 1;

    public Room $room;
    public array $prices = [];
    public array $listsForFields = [];
    public array $selectedFeatures = [];
    public mixed $features;
    public $stop_sales = [];
    public array $roomTypes;
    public array $seasons;
    public $stop_sale_end_date;
    public $stop_sale_start_date;
    public $available;

    public function mount(Room $room)
    {
        $this->room = $room;
        $this->room['active'] = $room->active == 1 ? 1 : 0;
        $this->prices = $this->room->prices->toArray();
        foreach($this->prices as $k => $v) {
            $this->prices[$k]['date'] = $v['start_date'] . ' to ' . $v['end_date'];
        }
        $this->available = $this->room->start_date . ' to ' .  $this->room->end_date;
        $this->selectedFeatures = $this->room->features()->pluck('feature_id')->toArray();
        $this->mediaCollections = [
            'room_gallery' => $this->room->gallery,
        ];
        $this->rule['adult']['pricing']     = $this->room->adult_pricing;
        $this->rule['adult']['capacity']    = $this->room->adult_capacity;
        $this->rule['adult']['extra_beds']  = $this->room->adult_extra_beds;
        $this->rule['child']['pricing']     = $this->room->child_pricing;
        $this->rule['child']['capacity']    = $this->room->child_capacity;
        $this->rule['child']['extra_beds']  = $this->room->child_extra_beds;
        $this->rule['infant']['pricing']    = $this->room->infant_pricing;
        $this->rule['infant']['capacity']    = $this->room->infant_capacity;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.hotels.edit-room')->layout('layouts.admin', ['title' => 'Edit Room']);
    }

    public function saveStopSale()
    {
        $this->validate([
            'stop_sale_start_date'  => 'required',
            'stop_sale_end_date'    => 'required',
        ]);
        $this->stop_sales = [
            'start_date'    => $this->stop_sale_start_date,
            'end_date'      => $this->stop_sale_end_date,
        ];
        StopSale::create([
            'reference_id'  => $this->room->id,
            'start_date'    => Carbon::parse($this->stop_sale_start_date),
            'end_date'      => Carbon::parse($this->stop_sale_end_date),
        ]);
        $this->getStopSale();
    }

    public function removeStopSale($id)
    {
        StopSale::find($id)->delete();
        $this->getStopSale();
    }

    public function updatedAvailable($k)
    {
        $dates = explode(' to ', $k);
        $startDate  = $dates[0] ?? null;
        $endDate    = $dates[1] ?? null;
        $this->room['start_date']   = $startDate;
        $this->room['end_date']     = $endDate;
    }

    public function save()
    {
        $this->room->adult_pricing      = $this->rule['adult']['pricing'];
        $this->room->adult_capacity     = $this->rule['adult']['capacity'];
        $this->room->adult_extra_beds   = $this->rule['adult']['extra_beds'];
        $this->room->child_pricing      = $this->rule['child']['pricing'];
        $this->room->child_capacity     = $this->rule['child']['capacity'];
        $this->room->child_extra_beds   = $this->rule['child']['extra_beds'];
        $this->room->infant_pricing     = $this->rule['infant']['pricing'];
        $this->room->infant_capacity    = $this->rule['infant']['capacity'];
        $this->validate();

        try {
            $this->room->save();
            // Check if the dates are within the range of the room dates
            foreach ($this->prices as $price) {
                $price_start_date   = $price['start_date'] instanceof Carbon ? $price['start_date'] : Carbon::parse($price['start_date']);
                $price_end_date     = $price['end_date'] instanceof Carbon ? $price['end_date'] : Carbon::parse($price['end_date']);
                $room_start_date    = $this->room->start_date instanceof Carbon ? $this->room->start_date : Carbon::parse($this->room->start_date);
                $room_end_date      = $this->room->end_date instanceof Carbon ? $this->room->end_date : Carbon::parse($this->room->end_date);
                if ($price_start_date < $room_start_date || $price_end_date > $room_end_date) {
                    throw new \ErrorException('Price dates must be within the range of the room dates');
                }
            }

            // Check for overlapping dates
            foreach ($this->prices as $index => $price) {
                $startDate = Carbon::parse($price['start_date']);
                $endDate = Carbon::parse($price['end_date']);
                foreach ($this->prices as $innerIndex => $innerPrice) {
                    if ($index !== $innerIndex) {
                        $innerStartDate = Carbon::parse($innerPrice['start_date']);
                        $innerEndDate = Carbon::parse($innerPrice['end_date']);
                        if ($startDate->lte($innerEndDate) && $endDate->gte($innerStartDate)) {
                            throw new \ErrorException('Price dates should not overlap');
                        }
                    }
                }
            }

            // Check for complete coverage
            $expectedDates = $this->generateDateRange(Carbon::parse($this->room->start_date), Carbon::parse($this->room->end_date));
            $providedDates = [];
            foreach ($this->prices as $price) {
                $priceStartDate = Carbon::parse($price['start_date']);
                $priceEndDate = Carbon::parse($price['end_date']);
                $priceRange = $this->generateDateRange($priceStartDate, $priceEndDate);
                $providedDates = array_merge($providedDates, $priceRange);
            }

            sort($expectedDates);
            sort($providedDates);
            if ($expectedDates !== $providedDates) {
                throw new \ErrorException('Price dates should cover the entire range of room dates');
            }

            // If all checks pass, proceed with saving data
            $pricesArray = $this->prices;
            unset($pricesArray['date']);
            $this->room->prices()->delete();
            $this->room->prices()->createMany($pricesArray);
            $this->room->features()->sync($this->selectedFeatures);
            $this->syncMedia();
            return redirect()->route('admin.hotels.rooms', $this->room->hotel_id);
        } catch(\Exception $error) {
            $this->addError('dates', $error->getMessage());
        }
    }

    // Function to generate date range between two Carbon objects
    protected function generateDateRange($start, $end): array
    {
        if (!$start instanceof Carbon || !$end instanceof Carbon) {
            throw new InvalidArgumentException('Arguments must be instances of Carbon');
        }
        $dates = [];
        $current = $start->copy(); // Ensure $start is a Carbon instance
        while ($current->lte($end)) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }
        return $dates;
    }

    public function updatedPrices($v, $k): void
    {
        if(str_contains($k, 'date')) {
            $dates = explode(" to ", $v);
            if(count($dates) == 2) {
                $parts = explode(".", $k);
                $key = (int) $parts[0];
                $this->prices[$key]['start_date']   = $dates[0];
                $this->prices[$key]['end_date']     = $dates[1];
            } else if(count($dates) == 1) {
                $parts = explode(".", $k);
                $key = (int) $parts[0];
                $this->prices[$key]['start_date']   = $dates[0];
                $this->prices[$key]['end_date']     = $dates[0];
            }
        }
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->room->id]));
        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    public function getStopSale()
    {
        $this->stop_sales = StopSale::where('reference_id', $this->room->id)->where('type', 'room')->get();
    }

    public function deletePrice($index): void
    {
        unset($this->prices[$index]);
        $this->prices = array_values($this->prices);
    }

    public function addPrice(): void
    {
        $this->prices[] = [
            'start_date'    => null,
            'end_date'      => null,
            'adult_1'       => null,
            'adult_2'       => null,
            'adult_3'       => null,
            'adult_4'       => null,
            'child_1'       => null,
            'child_2'       => null,
            'child_3'       => null,
            'child_4'       => null,
            'infant_1'      => null
        ];
    }

    protected function rules(): array
    {
        return [
            'room.units' => [
                'required'
            ],
            'room.room_type_id' => [
                'required'
            ],
            'room.start_date' => [
                'required',
            ],
            'room.end_date' => [
                'required',
            ],
            'room.max_capacity' => [
                'numeric', 'required'
            ],
            'room.active' => [
                'nullable'
            ],
            'room.description' => 'nullable',
            'room.hotel_id' => 'required',

            'prices.*.start_date' => [
                'required',
            ],
            'prices.*.end_date' => [
                'nullable',
            ],
            'prices.*.adult_1' => [
                'nullable'
            ],
            'prices.*.adult_2' => [
                'nullable'
            ],
            'prices.*.adult_3' => [
                'nullable'
            ],
            'prices.*.adult_4' => [
                'nullable'
            ],
            'prices.*.child_1' => [
                'nullable'
            ],
            'prices.*.child_2' => [
                'nullable'
            ],
            'prices.*.child_3' => [
                'nullable'
            ],
            'prices.*.child_4' => [
                'nullable'
            ],
            'prices.*.infant_1' => [
                'nullable'
            ],
            'selectedFeatures' => 'nullable',
        ];
    }

    protected function initListsForFields(): void
    {
        $this->features                       = Feature::query()->where('type', 'room')->whereNull('parent_id')->with('children')->get();
        $this->listsForFields['roomTypes']    = RoomType::query()->pluck('name', 'id')->toArray();
    }
}

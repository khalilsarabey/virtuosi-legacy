<?php

namespace App\Http\Livewire\Frontend\Hotels;

use App\Http\Controllers\Connections\Client\ApiClientSearchController;
use App\Models\Hotel;
use App\Services\Local\HotelService;
use Livewire\Component;

class Show extends Component
{
    public $source;
    public $query;
    public $hotel;
    public $rooms;
    public $hotel_price;
    public $plan = 0;
    public $plan_price = 0;
    public $startDate;
    public $endDate;
    public $requestedRooms = 1;
    public $selection = [];
    public $selectedKeys = [];
    /**
     * @var mixed|string
     */
    public mixed $type;
    public $mealPlans = [];

    public int $duration = 0;

    public function mount($id)
    {

        $this->query = request()->except('_token');

        $api = $this->query['api'] ?? null;

        $data = $this->query;

        if(!isset($data['type'])) {
            return redirect()->to('/');
        };

        $this->setDates($data);

        $this->type = $data['type'];

        $results = [
            'hotel'     => [],
            'rooms'     => [],
            'duration'  => [],
        ];

        if($api && auth()->user()->agency_id == 1) {
            $results = ApiClientSearchController::getHotelDetails([
                'id'         => $id,
                'api'        => $api,
                'start_date' => $this->startDate,
                'end_date'   => $this->endDate,
                'rooms'      => json_decode($data['rooms'], true),
                'type'       => $data['type']
            ]);
            if(count($results) == 0) {
                return redirect()->to('/');
            }
            $this->source = $results['source'];
            $results = $results['data'];
        } else {
            $results = HotelService::get_hotel_details($id, [
                'start_date' => $this->startDate,
                'end_date'   => $this->endDate,
                'rooms'      => json_decode($data['rooms'], true),
                'type'       => $data['type']
            ]);
        }

        $this->hotel    = $results['hotel'];
        $this->rooms    = $results['rooms'];
        $this->duration = $results['duration'];

        $mealPlans = ['bb', 'hb', 'fb', 'ai', 'sai', 'uai'];

        foreach ($mealPlans as $plan) {
            if (is_numeric($this->hotel[$plan])) {
                $this->mealPlans[$plan] = $this->hotel[$plan];
            }
        }
    }

    public function updatedRooms($key, $value): void
    {
        if(array_key_exists($key, $this->mealPlans)) {
            $array = explode('.', $value);
            $this->rooms[$array[0]][$array[1]][$array[2]]['meal_price'] = $this->mealPlans[$key];
        }
    }

    public function select($requestedRoomKey, $roomKey): void
    {
        // Check if there's an existing selection for the requestedRoomKey
        if (isset($this->selection[(int)$requestedRoomKey])) {
            $oldRoomId = $this->selection[(int)$requestedRoomKey]['room_id'];
            // Find and decrease the bookings_count of the previously selected room
            foreach ($this->rooms[$requestedRoomKey]['results'] as $key => $value) {
                if ($value['id'] == $oldRoomId) {
                    $this->rooms[$requestedRoomKey]['results'][$key]['bookings_count'] -= 1;
                }
            }
        }

        // Update the selection for the requestedRoomKey with the new room
        $this->selection[(int)$requestedRoomKey] = [
            'room_id'       => $this->rooms[(int)$requestedRoomKey]['results'][(int)$roomKey]['id'],
            'meal_plan'     => $this->rooms[(int)$requestedRoomKey]['results'][(int)$roomKey]['meal_plan'] ?? null,
            'meal_price'    => $this->rooms[(int)$requestedRoomKey]['results'][(int)$roomKey]['meal_price'] ?? null,
            'requested'     => $this->rooms[(int)$requestedRoomKey]['requested']
        ];

        // Increase the bookings_count of the newly selected room
        foreach ($this->rooms as $key => $value) {
            foreach ($value['results'] as $k => $v) {
                if ($v['id'] == $this->rooms[(int)$requestedRoomKey]['results'][(int)$roomKey]['id']) {
                    $this->rooms[$key]['results'][$k]['bookings_count'] += 1;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.frontend.hotels.show');
    }

    private function setDates($data): void
    {
        if (isset($data['date'])) {
            [$this->startDate, $this->endDate] = explode(' to ', $data['date']);
        } elseif (isset($data['dep_date']) && isset($data['ret_date'])) {
            $this->startDate = $data['dep_date'];
            $this->endDate = $data['ret_date'];
        }
    }
}

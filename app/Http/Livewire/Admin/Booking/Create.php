<?php

namespace App\Http\Livewire\Admin\Booking;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public Booking $booking;

    public array $listsForFields = [];

    public function mount(Booking $booking)
    {
        $this->booking           = $booking;
        $this->booking->infants  = '0';
        $this->booking->adults   = '0';
        $this->booking->children = '0';
        $this->booking->price    = '0';
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.booking.create');
    }

    public function submit()
    {
        $this->validate();

        $this->booking->save();

        return redirect()->route('admin.bookings.index');
    }

    protected function rules(): array
    {
        return [
            'booking.user_id' => [
                'integer',
                'exists:users,id',
                'required',
            ],
            'booking.reference' => [
                'string',
                'nullable',
            ],
            'booking.status' => [
                'string',
                'nullable',
            ],
            'booking.type' => [
                'string',
                'nullable',
            ],
            'booking.flight_id' => [
                'integer',
                'exists:flights,id',
                'required',
            ],
            'booking.dep_flight_id' => [
                'integer',
                'exists:flights,id',
                'required',
            ],
            'booking.ret_flight_id' => [
                'integer',
                'exists:flights,id',
                'nullable',
            ],
            'booking.infants' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'booking.adults' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'booking.children' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'booking.price' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'booking.payload' => [
                'string',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['user']       = User::pluck('name', 'id')->toArray();
        $this->listsForFields['flight']     = Flight::pluck('dep_date', 'id')->toArray();
        $this->listsForFields['dep_flight'] = Flight::pluck('dep_date', 'id')->toArray();
        $this->listsForFields['ret_flight'] = Flight::pluck('dep_date', 'id')->toArray();
    }
}

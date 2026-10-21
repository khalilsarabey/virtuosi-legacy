<?php

namespace App\Http\Livewire\Admin\Booking;

use App\Models\Booking;
use Livewire\Component;

class EditHotelBooking extends Component
{
    public Booking $booking;

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.admin.booking.edit-hotel-booking');
    }

    protected function rules()
    {
        return [
            'booking.hotel_id' => 'required',
            'booking.dep_date' => 'required',
            'booking.ret_date' => 'required',
        ];
    }
}

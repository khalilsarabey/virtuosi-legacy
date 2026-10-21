<?php

namespace App\Http\Livewire\Frontend;

use App\Models\AirlineBooking;
use App\Models\Booking;
use Livewire\Component;

class MyBookings extends Component
{
    public $profile;
    protected $bookings;
    public $booking_date;
    public $reference;
    protected $paginationTheme = 'bootstrap';
    public $filter = 'charter';
    public function mount()
    {
        $query = Booking::where('user_id', auth()->user()->id)
            ->where('agency_id', auth()->user()->agency_id)
            ->with(['tickets', 'depFlight', 'depFlight.airline', 'retFlight', 'retFlight.airline'])
            ->orderBy('created_at', 'DESC');;
        if($this->filter == 'charter')
        {
            $query->whereNull('hotel_id')->whereNull('hotel_name')->whereIn('type', ['roundtrip', 'multi', 'fixed', 'oneway']);
        }
        if($this->filter == 'packages')
        {
            $query->where('type', 'roundtrip')->where(function($q) {
                $q->whereNotNull('hotel_id')->orWhereNotNull('hotel_name');
            });
        }
        if($this->filter == 'hotels')
        {
            $query->where('type', 'hotel');
        }
        if(!empty($this->reference)) {
            $query->where('reference', 'like', '%' . $this->reference . '%');
        }
        if(!empty($this->booking_date)) {
            $query->whereDate('created_at', $this->booking_date);
        }
        $this->profile = auth()->user();
        $this->bookings = $query->paginate(50);
    }
    public function render()
    {
        $bookings = $this->bookings;
        return view('livewire.frontend.my-bookings', compact('bookings'));
    }

    public function airline($type)
    {
        if($type == 'low-cost') {
            $this->filter = 'low-cost';
            $query = AirlineBooking::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC');
            $this->bookings = $query->paginate(50);
        }
        if($type == 'charter') {
            $this->filter = 'charter';
            $this->mount();
        }
        if($type == 'packages')
        {
            $this->filter = 'packages';
            $this->mount();
        }
        if($type == 'hotels')
        {
            $this->filter = 'hotels';
            $this->mount();
        }
    }

    public function search()
    {
        $this->mount();
    }
}

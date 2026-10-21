<?php

namespace App\Http\Livewire\Admin\Booking;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
{
    public Ticket $ticket;
    public $ticketOldData;
    public array $listsForFields = [];

    public function mount($id)
    {
        $this->ticket = Ticket::find($id);
        $this->ticketOldData = $this->ticket;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.booking.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->ticket->full_name = $this->ticket->first_name . ' ' . $this->ticket->last_name;
            $this->ticket->save();

            $booking = Booking::where('id', $this->ticket->booking_id)->with(['tickets', 'rooms'])->first();

            if ($booking->rooms->count() > 0) {
                foreach ($booking->rooms as $room) {
                    if (
                        $room->title == $this->ticketOldData->title &&
                        $room->first_name == $this->ticketOldData->first_name &&
                        $room->last_name == $this->ticketOldData->last_name
                    ) {
                        $room->update([
                            'title'       => $this->ticket->title,
                            'full_name'   => $this->ticket->full_name,
                            'first_name'  => $this->ticket->first_name,
                            'last_name'   => $this->ticket->last_name,
                        ]);
                    }
                }
            }

            if ($booking->tickets->count() > 0) {
                foreach ($booking->tickets as $ticket) {
                    if (
                        $ticket->title == $this->ticketOldData->title &&
                        $ticket->first_name == $this->ticketOldData->first_name &&
                        $ticket->last_name == $this->ticketOldData->last_name
                    ) {
                        $ticket->title = $this->ticket->title;
                        $ticket->full_name = $this->ticket->full_name;
                        $ticket->first_name = $this->ticket->first_name;
                        $ticket->last_name = $this->ticket->last_name;
                        $ticket->save();
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('admin.bookings');
    }


    protected function rules(): array
    {
        return [
            'ticket.title' => [
                'string',
                'required',
            ],
            'ticket.full_name' => [
                'string'
            ],
            'ticket.first_name' => [
                'string',
                'required',
            ],
            'ticket.last_name' => [
                'string',
                'required',
            ],
            'ticket.date_of_birth' => [
                'required',
                'date_format:' . config('project.date_format'),
            ],
            'ticket.phone' => [
                'string',
                'required',
            ],
            'ticket.nationality' => [
                'string',
                'required',
            ]
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['title']      = ['Mr.' => 'Mr.', 'Miss.' => 'Miss.', 'Mrs.' => 'Mrs', 'Infant.' => 'Infant.', 'Child.' => 'Child.'];
    }
}

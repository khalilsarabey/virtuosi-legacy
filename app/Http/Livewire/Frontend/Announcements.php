<?php

namespace App\Http\Livewire\Frontend;

use App\Models\PricingAnnouncement;
use Livewire\Component;
use Livewire\WithPagination;

class Announcements extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.frontend.announcements', [
            'announcements' => PricingAnnouncement::orderBy('created_at', 'desc')->paginate(5),
        ]);
    }
}

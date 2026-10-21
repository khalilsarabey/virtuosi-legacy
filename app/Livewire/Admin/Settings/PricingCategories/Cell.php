<?php

namespace App\Livewire\Admin\Settings\PricingCategories;

use Livewire\Component;

class Cell extends Component
{
    public function render($categories = [], $depth = 0)
    {
        return view('livewire.admin.settings.pricing-categories.cell', [
            'categories' => $categories,
            'depth' => $depth,
        ]);
    }
}

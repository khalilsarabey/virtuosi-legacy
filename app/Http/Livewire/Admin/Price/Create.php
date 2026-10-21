<?php

namespace App\Http\Livewire\Admin\Price;

use App\Models\Flight;
use App\Models\Price;
use Livewire\Component;

class Create extends Component
{
    public Price $price;

    public array $listsForFields = [];

    public function mount(Price $price)
    {
        $this->price                = $price;
        $this->price->adult_price   = '0';
        $this->price->activate_once = '0';
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.price.create');
    }

    public function submit()
    {
        $this->validate();

        $this->price->save();

        return redirect()->route('admin.prices.index');
    }

    protected function rules(): array
    {
        return [
            'price.flight_id' => [
                'integer',
                'exists:flights,id',
                'required',
            ],
            'price.adult_price' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'required',
            ],
            'price.child_price' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'required',
            ],
            'price.infant_price' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'required',
            ],
            'price.activate_once' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['flight'] = Flight::pluck('dep_date', 'id')->toArray();
    }
}

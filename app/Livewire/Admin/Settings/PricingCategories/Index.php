<?php

namespace App\Livewire\Admin\Settings\PricingCategories;

use App\Livewire\WithConfirmation;
use App\Models\PricingCategory;
use Livewire\Component;

class Index extends Component
{
    use WithConfirmation;

    public $categories;
    public $addingCategory;
    public $editingCategory;
    public $categoryName;
    public $categoryId;

    public function render()
    {
        $this->categories = PricingCategory::all();
        return view('livewire.admin.settings.pricing-categories.index')->layout('layouts.admin');
    }

    public function add()
    {
        $this->addingCategory = !$this->addingCategory;
    }

    public function edit($id)
    {
        $category = PricingCategory::find($id);
        $this->categoryName = $category->name;
        $this->categoryId = $category->id;
        $this->editingCategory = true;
    }

    public function save()
    {
        PricingCategory::create([
            'name' => $this->categoryName
        ]);
        $this->categoryName = '';
    }

    public function update($id)
    {
        $category = PricingCategory::find($id);
        $category->update([
            'name' => $this->categoryName
        ]);
        $this->categoryName = '';
        $this->editingCategory = false;
    }

    public function delete($id)
    {
        PricingCategory::find($id)->delete();
    }
}

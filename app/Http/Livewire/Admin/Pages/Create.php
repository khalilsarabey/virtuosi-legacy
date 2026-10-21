<?php
namespace App\Http\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;

class Create extends Component
{
    public $title = '';
    public $meta_title = '';
    public $meta_description = '';
    public $is_homepage = false;
    public $status = true;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'meta_title' => 'nullable|max:255',
        'meta_description' => 'nullable|max:255',
        'is_homepage' => 'boolean',
        'status' => 'boolean',
    ];

    public function createPage()
    {
        $this->validate();

        $page = Page::create([
            'title' => $this->title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'is_homepage' => $this->is_homepage,
            'status' => $this->status,
            'content' => [],
        ]);

        $this->emit('pageCreated');

        return redirect()->route('admin.pages.edit', $page->id);
    }

    public function render()
    {
        return view('livewire.admin.pages.create')->layout('layouts.admin');
    }
}

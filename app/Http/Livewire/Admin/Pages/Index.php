<?php

namespace App\Http\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $confirmingPageDeletion = false;
    public $pageIdBeingDeleted = null;

    protected $listeners = ['pageCreated' => '$refresh', 'pageUpdated' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmPageDeletion($pageId)
    {
        $this->confirmingPageDeletion = true;
        $this->pageIdBeingDeleted = $pageId;
    }

    public function deletePage()
    {
        Page::find($this->pageIdBeingDeleted)->delete();
        $this->confirmingPageDeletion = false;
        $this->pageIdBeingDeleted = null;
        session()->flash('message', 'Page deleted successfully.');
    }

    public function setAsHomepage($pageId)
    {
        $page = Page::find($pageId);
        $page->is_homepage = true;
        $page->save();

        session()->flash('message', 'Homepage set successfully.');
    }

    /**
     * Toggle the status of a page (active/inactive)
     */
    public function toggleStatus($pageId)
    {
        $page = Page::find($pageId);
        $page->status = !$page->status;
        $page->save();

        $status = $page->status ? 'active' : 'inactive';
        session()->flash('message', "Page status changed to {$status} successfully.");
    }

    public function render()
    {
        $pages = Page::where('title', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.pages.index', [
            'pages' => $pages
        ])->layout('layouts.admin');
    }
}

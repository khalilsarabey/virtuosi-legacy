<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;
    
    // Helper method to create a new row of columns based on section type
    private function createColumnsRow($sectionType)
    {
        // Define column sizes based on section type
        $columnSizes = match($sectionType) {
            '6x6_columns' => [6, 6],
            '4x4x4_columns' => [4, 4, 4],
            '3x3x3x3_columns' => [3, 3, 3, 3],
            '3x9_columns' => [3, 9],
            '9x3_columns' => [9, 3],
            // New column layouts
            '6x3x3_columns' => [6, 3, 3],
            '3x3x6_columns' => [3, 3, 6],
            '3x6x3_columns' => [3, 6, 3],
            '6x2x2x2_columns' => [6, 2, 2, 2],
            '2x2x2x6_columns' => [2, 2, 2, 6],
            '2x2x3x3_columns' => [2, 2, 3, 3],
            '3x3x2x2_columns' => [3, 3, 2, 2],
            '3x2x2x2x3_columns' => [3, 2, 2, 2, 3],
            // Legacy support
            '2_columns' => [6, 6],
            '3_columns' => [4, 4, 4],
            '4_columns' => [3, 3, 3, 3],
            default => []
        };
        
        $columns = [];
        foreach ($columnSizes as $size) {
            $columns[] = [
                'image' => '',
                'link_type' => 'none',
                'custom_url' => '',
                'category_id' => null,
                'size' => $size // Store the column size
            ];
        }
        
        return $columns;
    }

    public $page;
    public $pageId;
    public $title;
    public $meta_title;
    public $meta_description;
    public $is_homepage;
    public $status;
    public $content = [];
    
    // For category display in the admin panel
    public $categories;

    public $showSectionModal = false;
    public $sectionType = '';
    public $sectionName = '';
    public $editingSectionIndex = null;

    public $showImageModal = false;
    public $currentColumnIndex = null;
    public $currentSectionIndex = null;
    public $tempImage;

    public $showLinkModal = false;
    public $linkType = 'none';
    public $customUrl = '';
    public $selectedCategoryId = null;
    public $currentSlideIndex = null;

    protected $listeners = ['refreshPage' => '$refresh'];

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'meta_title' => 'nullable|max:255',
        'meta_description' => 'nullable|max:255',
        'is_homepage' => 'boolean',
        'status' => 'boolean',
        'content' => 'array',
    ];

    public function mount($id)
    {
        $this->pageId = $id;
        $this->page = Page::findOrFail($id);
        $this->title = $this->page->title;
        $this->meta_title = $this->page->meta_title;
        $this->meta_description = $this->page->meta_description;
        $this->is_homepage = $this->page->is_homepage;
        $this->status = $this->page->status;
        $this->content = $this->page->content ?: [];
        
        // Load categories for display in the admin panel
        $this->categories = Category::all();
    }

    public function updatePage()
    {
        $this->validate();

        $this->page->update([
            'title' => $this->title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'is_homepage' => $this->is_homepage,
            'status' => $this->status,
            'content' => $this->content,
        ]);

        session()->flash('message', 'Page updated successfully.');
        $this->dispatch('pageUpdated');
    }

    public function openSectionModal($index = null)
    {
        $this->editingSectionIndex = $index;
        if ($index !== null) {
            $this->sectionType = $this->content[$index]['type'];
            $this->sectionName = $this->content[$index]['name'] ?? '';
        } else {
            $this->sectionType = '';
            $this->sectionName = '';
        }
        $this->showSectionModal = true;
        $this->dispatch('showSectionModal');
    }

    public function addRowToSection($sectionIndex)
    {
        $sectionType = $this->content[$sectionIndex]['type'];
        $this->content[$sectionIndex]['rows'][] = $this->createColumnsRow($sectionType);
    }
    
    public function removeRowFromSection($sectionIndex, $rowIndex)
    {
        if (count($this->content[$sectionIndex]['rows']) > 1) {
            unset($this->content[$sectionIndex]['rows'][$rowIndex]);
            $this->content[$sectionIndex]['rows'] = array_values($this->content[$sectionIndex]['rows']);
        }
    }
    
    /**
     * Remove a slide from a slider section
     */
    public function removeSlide($sectionIndex, $slideIndex)
    {
        if (isset($this->content[$sectionIndex]['slides'][$slideIndex])) {
            unset($this->content[$sectionIndex]['slides'][$slideIndex]);
            $this->content[$sectionIndex]['slides'] = array_values($this->content[$sectionIndex]['slides']);
        }
    }

    /**
     * Select a section type for the visual layout selector
     */
    public function selectSectionType($type)
    {
        $this->sectionType = $type;
    }
    
    /**
     * Close the section modal
     */
    public function closeSectionModal()
    {
        $this->showSectionModal = false;
        $this->sectionType = '';
        $this->sectionName = '';
        $this->editingSectionIndex = null;
        $this->dispatch('hideSectionModal');
    }
    
    public function addSection()
    {
        if (empty($this->sectionType)) {
            return;
        }

        // If editing an existing section, preserve its content
        if ($this->editingSectionIndex !== null) {
            // Keep the existing section data but update type and name
            $existingSection = $this->content[$this->editingSectionIndex];
            $existingSection['type'] = $this->sectionType;
            $existingSection['name'] = $this->sectionName;
            
            // If section type has changed, reinitialize its structure
            if ($existingSection['type'] !== $this->content[$this->editingSectionIndex]['type']) {
                // Initialize section based on new type
                switch ($this->sectionType) {
                    case 'full_width_slider':
                        $existingSection['slides'] = [];
                        break;
                        
                    case 'full_width_image':
                        $existingSection['image'] = '';
                        $existingSection['link_type'] = 'none';
                        $existingSection['custom_url'] = '';
                        $existingSection['category_id'] = null;
                        break;
                        
                    // All column-based layouts
                    case '6x6_columns':
                    case '4x4x4_columns':
                    case '3x3x3x3_columns':
                    case '3x9_columns':
                    case '9x3_columns':
                    // New column layouts
                    case '6x3x3_columns':
                    case '3x3x6_columns':
                    case '3x6x3_columns':
                    case '6x2x2x2_columns':
                    case '2x2x2x6_columns':
                    case '2x2x3x3_columns':
                    case '3x3x2x2_columns':
                    case '3x2x2x2x3_columns':
                    // Legacy support
                    case '2_columns':
                    case '3_columns':
                    case '4_columns':
                        // Initialize with a single row of columns
                        $existingSection['rows'] = [
                            $this->createColumnsRow($this->sectionType)
                        ];
                        break;
                }
            }
            
            $this->content[$this->editingSectionIndex] = $existingSection;
        } else {
            // Creating a new section
            $section = [
                'type' => $this->sectionType,
                'name' => $this->sectionName,
            ];

            // Initialize section based on type
            switch ($this->sectionType) {
                case 'full_width_slider':
                    $section['slides'] = [];
                    break;
                    
                case 'full_width_image':
                    $section['image'] = '';
                    $section['link_type'] = 'none';
                    $section['custom_url'] = '';
                    $section['category_id'] = null;
                    break;
                    
                // All column-based layouts
                case '6x6_columns':
                case '4x4x4_columns':
                case '3x3x3x3_columns':
                case '3x9_columns':
                case '9x3_columns':
                // New column layouts
                case '6x3x3_columns':
                case '3x3x6_columns':
                case '3x6x3_columns':
                case '6x2x2x2_columns':
                case '2x2x2x6_columns':
                case '2x2x3x3_columns':
                case '3x3x2x2_columns':
                case '3x2x2x2x3_columns':
                // Legacy support
                case '2_columns':
                case '3_columns':
                case '4_columns':
                    // Initialize with a single row of columns
                    $section['rows'] = [
                        $this->createColumnsRow($this->sectionType)
                    ];
                    break;
            }
            
            $this->content[] = $section;
        }

        $this->showSectionModal = false;
        $this->sectionType = '';
        $this->sectionName = '';
        $this->editingSectionIndex = null;
        $this->dispatch('hideSectionModal');
    }

    public function removeSection($index)
    {
        unset($this->content[$index]);
        $this->content = array_values($this->content);
    }
    
    /**
     * Move a section up in the order
     * 
     * @param int $index The index of the section to move up
     */
    public function moveSectionUp($index)
    {
        if ($index <= 0 || $index >= count($this->content)) {
            return;
        }
        
        // Swap the section with the one above it
        $temp = $this->content[$index - 1];
        $this->content[$index - 1] = $this->content[$index];
        $this->content[$index] = $temp;
    }
    
    /**
     * Move a section down in the order
     * 
     * @param int $index The index of the section to move down
     */
    public function moveSectionDown($index)
    {
        if ($index < 0 || $index >= count($this->content) - 1) {
            return;
        }
        
        // Swap the section with the one below it
        $temp = $this->content[$index + 1];
        $this->content[$index + 1] = $this->content[$index];
        $this->content[$index] = $temp;
    }

    public $currentRowIndex = null;
    
    public function openImageModal($sectionIndex, $rowIndex = null, $columnIndex = null)
    {
        $this->currentSectionIndex = $sectionIndex;
        $this->currentRowIndex = $rowIndex;
        $this->currentColumnIndex = $columnIndex;
        $this->tempImage = null;
        $this->showImageModal = true;
        $this->dispatch('showImageModal');
    }

    public function uploadImage()
    {
        $this->validate([
            'tempImage' => 'required|image|max:1024',
        ]);

        $path = $this->tempImage->store('public/pages');
        $url = Storage::url($path);

        if ($this->currentRowIndex !== null && $this->currentColumnIndex !== null) {
            // For multi-row column layouts
            $this->content[$this->currentSectionIndex]['rows'][$this->currentRowIndex][$this->currentColumnIndex]['image'] = $url;
        } else if ($this->currentColumnIndex !== null) {
            // For backward compatibility with old data structure
            if (isset($this->content[$this->currentSectionIndex]['columns'])) {
                $this->content[$this->currentSectionIndex]['columns'][$this->currentColumnIndex]['image'] = $url;
            }
        } else {
            if ($this->content[$this->currentSectionIndex]['type'] === 'full_width_slider') {
                if (!isset($this->content[$this->currentSectionIndex]['slides'])) {
                    $this->content[$this->currentSectionIndex]['slides'] = [];
                }
                $this->content[$this->currentSectionIndex]['slides'][] = [
                    'image' => $url,
                    'link_type' => 'none',
                    'custom_url' => '',
                    'category_id' => null
                ];
            } else {
                $this->content[$this->currentSectionIndex]['image'] = $url;
            }
        }

        $this->showImageModal = false;
        $this->tempImage = null;
        $this->dispatch('hideImageModal');
    }

    public function openLinkModal($sectionIndex, $rowIndex = null, $columnIndex = null, $slideIndex = null)
    {
        $this->currentSectionIndex = $sectionIndex;
        $this->currentRowIndex = $rowIndex;
        $this->currentColumnIndex = $columnIndex;
        $this->currentSlideIndex = $slideIndex;

        if ($rowIndex !== null && $columnIndex !== null) {
            // For multi-row column layouts
            $column = $this->content[$sectionIndex]['rows'][$rowIndex][$columnIndex];
            $this->linkType = $column['link_type'];
            $this->customUrl = $column['custom_url'];
            $this->selectedCategoryId = $column['category_id'];
        } else if ($columnIndex !== null) {
            // For backward compatibility with old data structure
            if (isset($this->content[$sectionIndex]['columns'])) {
                $column = $this->content[$sectionIndex]['columns'][$columnIndex];
                $this->linkType = $column['link_type'];
                $this->customUrl = $column['custom_url'];
                $this->selectedCategoryId = $column['category_id'];
            }
        } else if ($this->content[$sectionIndex]['type'] === 'full_width_slider' && $slideIndex !== null) {
            $slide = $this->content[$sectionIndex]['slides'][$slideIndex];
            $this->linkType = $slide['link_type'];
            $this->customUrl = $slide['custom_url'];
            $this->selectedCategoryId = $slide['category_id'];
        } else {
            $this->linkType = $this->content[$sectionIndex]['link_type'];
            $this->customUrl = $this->content[$sectionIndex]['custom_url'];
            $this->selectedCategoryId = $this->content[$sectionIndex]['category_id'];
        }

        $this->showLinkModal = true;
        $this->dispatch('showLinkModal');
    }

    public function saveLink()
    {
        if ($this->currentRowIndex !== null && $this->currentColumnIndex !== null) {
            // For multi-row column layouts
            $this->content[$this->currentSectionIndex]['rows'][$this->currentRowIndex][$this->currentColumnIndex]['link_type'] = $this->linkType;
            $this->content[$this->currentSectionIndex]['rows'][$this->currentRowIndex][$this->currentColumnIndex]['custom_url'] = $this->customUrl;
            $this->content[$this->currentSectionIndex]['rows'][$this->currentRowIndex][$this->currentColumnIndex]['category_id'] = $this->selectedCategoryId;
        } else if ($this->currentColumnIndex !== null) {
            // For backward compatibility with old data structure
            if (isset($this->content[$this->currentSectionIndex]['columns'])) {
                $this->content[$this->currentSectionIndex]['columns'][$this->currentColumnIndex]['link_type'] = $this->linkType;
                $this->content[$this->currentSectionIndex]['columns'][$this->currentColumnIndex]['custom_url'] = $this->customUrl;
                $this->content[$this->currentSectionIndex]['columns'][$this->currentColumnIndex]['category_id'] = $this->selectedCategoryId;
            }
        } else if ($this->content[$this->currentSectionIndex]['type'] === 'full_width_slider' && $this->currentSlideIndex !== null) {
            // For individual slides in a slider
            $this->content[$this->currentSectionIndex]['slides'][$this->currentSlideIndex]['link_type'] = $this->linkType;
            $this->content[$this->currentSectionIndex]['slides'][$this->currentSlideIndex]['custom_url'] = $this->customUrl;
            $this->content[$this->currentSectionIndex]['slides'][$this->currentSlideIndex]['category_id'] = $this->selectedCategoryId;
        } else {
            // For full width image or other section types
            $this->content[$this->currentSectionIndex]['link_type'] = $this->linkType;
            $this->content[$this->currentSectionIndex]['custom_url'] = $this->customUrl;
            $this->content[$this->currentSectionIndex]['category_id'] = $this->selectedCategoryId;
        }

        $this->showLinkModal = false;
        $this->dispatch('hideLinkModal');
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.admin.pages.edit', [
            'categories' => $categories
        ])->layout('layouts.admin');
    }
  }

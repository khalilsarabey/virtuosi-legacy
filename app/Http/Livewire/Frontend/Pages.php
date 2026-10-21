<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Page;
use App\Models\Category;
use Livewire\Component;

class Pages extends Component
{
    public $page;
    public $content;
    public $slug;

    public function mount($slug = null)
    {
        $this->slug = $slug;

        // If no slug is provided, show the homepage
        if ($slug === null) {
            $this->page = Page::query()->where('is_homepage', true)
                ->where('status', true)
                ->firstOrFail();
        } else {
            $this->page = Page::query()->where('slug', $slug)
                ->where('status', true)
                ->firstOrFail();
        }

        $this->content = $this->processPageContent($this->page->content);
    }

    /**
     * Process the page content to include category names
     */
    private function processPageContent($content)
    {
        // Get all category IDs used in the content
        $categoryIds = $this->extractCategoryIds($content);

        // If no category IDs are used, return the content as is
        if (empty($categoryIds)) {
            return $content;
        }

        // Get all categories in a single query
        $categories = Category::whereIn('id', $categoryIds)->get()->keyBy('id');

        // Process each section to add category information
        foreach ($content as &$section) {
            $section = $this->addCategoryInfo($section, $categories);
        }

        return $content;
    }

    /**
     * Extract all category IDs used in the page content
     */
    private function extractCategoryIds($content)
    {
        $categoryIds = [];

        foreach ($content as $section) {
            // Check section-level category
            if (isset($section['link_type']) && $section['link_type'] === 'category' && !empty($section['category_id'])) {
                $categoryIds[] = $section['category_id'];
            }

            // Check columns in multi-column sections
            if (isset($section['rows'])) {
                foreach ($section['rows'] as $row) {
                    foreach ($row as $column) {
                        if (isset($column['link_type']) && $column['link_type'] === 'category' && !empty($column['category_id'])) {
                            $categoryIds[] = $column['category_id'];
                        }
                    }
                }
            }

            // Legacy support for old column structure
            if (isset($section['columns'])) {
                foreach ($section['columns'] as $column) {
                    if (isset($column['link_type']) && $column['link_type'] === 'category' && !empty($column['category_id'])) {
                        $categoryIds[] = $column['category_id'];
                    }
                }
            }

            // Check slides in slider sections
            if (isset($section['slides'])) {
                foreach ($section['slides'] as $slide) {
                    if (isset($slide['link_type']) && $slide['link_type'] === 'category' && !empty($slide['category_id'])) {
                        $categoryIds[] = $slide['category_id'];
                    }
                }
            }
        }

        return array_unique($categoryIds);
    }

    /**
     * Add category information to a section
     */
    private function addCategoryInfo($section, $categories)
    {
        // Add category info to section-level link
        if (isset($section['link_type']) && $section['link_type'] === 'category') {
            $categoryId = $section['category_id'];
            if (isset($categories[$categoryId])) {
                $section['category_name'] = $categories[$categoryId]->name;
                $section['category_slug'] = $categories[$categoryId]->slug;
                $section['category_show_title'] = $categories[$categoryId]->show_title;
            }
        }

        // Process columns in multi-column sections
        if (isset($section['rows'])) {
            foreach ($section['rows'] as &$row) {
                foreach ($row as &$column) {
                    if (isset($column['link_type']) && $column['link_type'] === 'category') {
                        $categoryId = $column['category_id'];
                        if (isset($categories[$categoryId])) {
                            $column['category_name'] = $categories[$categoryId]->name;
                            $column['category_slug'] = $categories[$categoryId]->slug;
                            $column['category_show_title'] = $categories[$categoryId]->show_title;
                        }
                    }
                }
            }
        }

        // Legacy support for old column structure
        if (isset($section['columns'])) {
            foreach ($section['columns'] as &$column) {
                if (isset($column['link_type']) && $column['link_type'] === 'category') {
                    $categoryId = $column['category_id'];
                    if (isset($categories[$categoryId])) {
                        $column['category_name'] = $categories[$categoryId]->name;
                        $column['category_slug'] = $categories[$categoryId]->slug;
                        $column['category_show_title'] = $categories[$categoryId]->show_title;
                    }
                }
            }
        }

        // Process slides in slider sections
        if (isset($section['slides'])) {
            foreach ($section['slides'] as &$slide) {
                if (isset($slide['link_type']) && $slide['link_type'] === 'category') {
                    $categoryId = $slide['category_id'];
                    if (isset($categories[$categoryId])) {
                        $slide['category_name'] = $categories[$categoryId]->name;
                        $slide['category_slug'] = $categories[$categoryId]->slug;
                        $slide['category_show_title'] = $categories[$categoryId]->show_title;
                    }
                }
            }
        }

        return $section;
    }

    public function render()
    {
        // Debug the content structure
        // dd($this->content);
        
        return view('livewire.frontend.pages', [
            'title' => $this->page->meta_title ?: $this->page->title,
            'description' => $this->page->meta_description
        ]);
    }
}

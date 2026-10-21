<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the homepage or a specific page by slug
     *
     * @param string|null $slug
     * @return \Illuminate\View\View
     */
    public function show($slug = null)
    {
        // If no slug is provided, show the homepage
        if ($slug === null) {
            $page = Page::query()->where('is_homepage', true)
                ->where('status', true)
                ->firstOrFail();
        } else {
            $page = Page::query()->where('slug', $slug)
                ->where('status', true)
                ->firstOrFail();
        }

        // Process the page content to include category names
        $content = $this->processPageContent($page->content);

        return view('pages.show', [
            'page' => $page,
            'content' => $content
        ]);
    }

    /**
     * Process the page content to include category names for any category IDs
     *
     * @param array $content
     * @return array
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
     *
     * @param array $content
     * @return array
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
            } elseif (isset($section['columns'])) {
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
     *
     * @param array $section
     * @param \Illuminate\Support\Collection $categories
     * @return array
     */
    private function addCategoryInfo($section, $categories)
    {
        // Process section-level category
        if (isset($section['link_type']) && $section['link_type'] === 'category' && !empty($section['category_id'])) {
            $categoryId = $section['category_id'];
            if (isset($categories[$categoryId])) {
                $section['category_name'] = $categories[$categoryId]->name;
                $section['category_slug'] = $categories[$categoryId]->slug;
            }
        }

        // Process columns in multi-column sections
        if (isset($section['rows'])) {
            foreach ($section['rows'] as &$row) {
                foreach ($row as &$column) {
                    if (isset($column['link_type']) && $column['link_type'] === 'category' && !empty($column['category_id'])) {
                        $categoryId = $column['category_id'];
                        if (isset($categories[$categoryId])) {
                            $column['category_name'] = $categories[$categoryId]->name;
                            $column['category_slug'] = $categories[$categoryId]->slug;
                        }
                    }
                }
            }
        } elseif (isset($section['columns'])) {
            foreach ($section['columns'] as &$column) {
                if (isset($column['link_type']) && $column['link_type'] === 'category' && !empty($column['category_id'])) {
                    $categoryId = $column['category_id'];
                    if (isset($categories[$categoryId])) {
                        $column['category_name'] = $categories[$categoryId]->name;
                        $column['category_slug'] = $categories[$categoryId]->slug;
                    }
                }
            }
        }

        // Process slides in slider sections
        if (isset($section['slides'])) {
            foreach ($section['slides'] as &$slide) {
                if (isset($slide['link_type']) && $slide['link_type'] === 'category' && !empty($slide['category_id'])) {
                    $categoryId = $slide['category_id'];
                    if (isset($categories[$categoryId])) {
                        $slide['category_name'] = $categories[$categoryId]->name;
                        $slide['category_slug'] = $categories[$categoryId]->slug;
                    }
                }
            }
        }

        return $section;
    }
}

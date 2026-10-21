<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit Page: {{ $page->title }}
                </h6>
            </div>
        </div>
        <form wire:submit="updatePage">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" wire:model.live="title">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" wire:model.live="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <!-- Page Builder Section -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Page Builder</h6>
                            <button class="btn btn-sm btn-primary" wire:click="openSectionModal">
                                <i class="fas fa-plus"></i> Add Section
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (empty($content))
                            <div class="text-center py-5">
                                <p class="text-muted">No sections added yet. Click "Add Section" to start building your
                                    page.</p>
                            </div>
                        @else
                            <div class="page-preview">
                                @foreach ($content as $sectionIndex => $section)
                                    <div class="section-wrapper mb-4">
                                        <div
                                            class="section-header d-flex justify-content-between align-items-center bg-light p-2 mb-2">
                                        <span class="font-weight-bold">
                                            {{ !empty($section['name']) ? $section['name'] : ucwords(str_replace('_', ' ', $section['type'])) }}
                                        </span>
                                            <div class="d-flex align-items-center">
                                                <!-- Move section up button -->
                                                <button class="btn btn-sm text-secondary mr-1"
                                                        wire:click="moveSectionUp({{ $sectionIndex }})"
                                                        {{ $sectionIndex == 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-arrow-up"></i>
                                                </button>

                                                <!-- Move section down button -->
                                                <button class="btn btn-sm text-secondary mr-1"
                                                        wire:click="moveSectionDown({{ $sectionIndex }})"
                                                        {{ $sectionIndex == count($content) - 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>

                                                <!-- Edit section button -->
                                                <button class="btn btn-sm text-primary mr-1" wire:click="openSectionModal({{ $sectionIndex }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- Delete section button -->
                                                <button class="btn btn-sm text-danger"
                                                        wire:click="removeSection({{ $sectionIndex }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="section-content">
                                            @if ($section['type'] === 'full_width_slider')
                                                <div class="slider-section">
                                                    <div class="d-flex justify-content-end mb-2">
                                                        <button class="btn btn-sm btn-secondary"
                                                                wire:click="openImageModal({{ $sectionIndex }})">
                                                            <i class="fas fa-plus"></i> Add Slide
                                                        </button>
                                                    </div>

                                                    @if (!empty($section['slides']))
                                                        <div class="row">
                                                            @foreach ($section['slides'] as $slideIndex => $slide)
                                                                <div class="col-md-3 mb-3">
                                                                    <div class="card">
                                                                        <img src="{{ $slide['image'] }}" class="card-img-top"
                                                                             alt="Slide Image"
                                                                             style="height: 150px; object-fit: cover;">
                                                                        <div class="card-body">
                                                                            <div class="d-flex justify-content-between">
                                                                                <button class="btn btn-sm btn-primary"
                                                                                        wire:click="openLinkModal({{ $sectionIndex }}, null, null, {{ $slideIndex }})">
                                                                                    <i class="fas fa-link"></i>
                                                                                </button>
                                                                                @if(isset($slide['link_type']) && $slide['link_type'] !== 'none')
                                                                                    <span class="text-dark small">
                                                                                        @if($slide['link_type'] === 'category')
                                                                                            @php
                                                                                                $category = $categories->firstWhere('id', $slide['category_id']);
                                                                                            @endphp
                                                                                            {{ $category ? $category->name : 'Category' }}
                                                                                        @else
                                                                                            {{ Str::limit($slide['custom_url'] ?? 'URL', 50) }}
                                                                                        @endif
                                                                                    </span>
                                                                                @endif
                                                                                <button type="button" class="btn btn-sm btn-danger" wire:click.prevent="removeSlide({{ $sectionIndex }}, {{ $slideIndex }})">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-center py-5 border">
                                                            <p class="text-muted">No slides added yet. Click "Add Slide" to add
                                                                images to the slider.</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif ($section['type'] === 'full_width_image')
                                                <div class="full-width-image-section">
                                                    <div class="text-center">
                                                        @if (!empty($section['image']))
                                                            <img src="{{ $section['image'] }}" class="img-fluid mb-3" alt="Full Width Image" style="max-height: 300px;">
                                                            <div class="d-flex justify-content-center">
                                                                <button class="btn btn-sm btn-primary"
                                                                        wire:click="openLinkModal({{ $sectionIndex }})">
                                                                    <i class="fas fa-link"></i>
                                                                </button>
                                                                @if(isset($section['link_type']) && $section['link_type'] !== 'none')
                                                                    <span class="badge badge-info ml-2">
                                                                        @if($section['link_type'] === 'category')
                                                                            @php
                                                                                $category = $categories->firstWhere('id', $section['category_id']);
                                                                            @endphp
                                                                            {{ $category ? $category->name : 'Category' }}
                                                                        @else
                                                                            URL
                                                                        @endif
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="text-center py-5 border"
                                                             wire:click="openImageModal({{ $sectionIndex }})">
                                                            <p class="text-muted">Click to add an image</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif (in_array($section['type'], ['2_columns', '3_columns', '4_columns', '6x6_columns', '4x4x4_columns', '3x3x3x3_columns', '3x9_columns', '9x3_columns', '6x3x3_columns', '3x3x6_columns', '3x6x3_columns', '6x2x2x2_columns', '2x2x2x6_columns', '2x2x3x3_columns', '3x3x2x2_columns', '3x2x2x2x3_columns']))
                                                <div class="columns-section">
                                                    <!-- Add Row Button -->
                                                    <div class="d-flex justify-content-end mb-3">
                                                        <button class="btn btn-sm btn-secondary" wire:click="addRowToSection({{ $sectionIndex }})">
                                                            <i class="fas fa-plus"></i> Add Row
                                                        </button>
                                                    </div>

                                                    @if (isset($section['rows']))
                                                        <!-- New multi-row structure -->
                                                        @foreach ($section['rows'] as $rowIndex => $row)
                                                             <div class="row mb-4 position-relative">
                                                                 <!-- Row controls -->
                                                                 <div class="position-absolute top-0 end-0 p-1 bg-light rounded" style="z-index: 100;">
                                                                     @if (count($section['rows']) > 1)
                                                                     <button type="button" class="btn btn-sm btn-danger"
                                                                             wire:click.prevent="removeRowFromSection({{ $sectionIndex }}, {{ $rowIndex }})">
                                                                         <i class="fas fa-trash"></i>
                                                                     </button>
                                                                     @endif
                                                                 </div>

                                                                <!-- Row columns -->
                                                                @foreach ($row as $columnIndex => $column)
                                                                    <div class="col-md-{{ $column['size'] ?? (12 / count($row)) }} mb-3">
                                                                        <div class="card h-100">
                                                                            <div class="card-body p-2 position-relative">
                                                                                @if (!empty($column['image']))
                                                                                    <img src="{{ $column['image'] }}" class="img-fluid mb-2" alt="Column Image">
                                                                                @else
                                                                                    <div class="bg-light p-3 text-center mb-2" style="min-height: 100px;">
                                                                                        <i class="fas fa-image text-muted"></i>
                                                                                    </div>
                                                                                @endif

                                                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                                                    <button type="button" class="btn btn-sm btn-primary"
                                                                                            wire:click="openImageModal({{ $sectionIndex }}, {{ $rowIndex }}, {{ $columnIndex }})">
                                                                                        <i class="fas fa-image"></i>
                                                                                    </button>

                                                                                    @if(isset($column['link_type']) && $column['link_type'] !== 'none')
                                                                                        <span class="text-dark small">
                                                                                            @if($column['link_type'] === 'category')
                                                                                                @php
                                                                                                    $category = $categories->firstWhere('id', $column['category_id']);
                                                                                                @endphp
                                                                                                {{ $category ? $category->name : 'Category' }}
                                                                                            @else
                                                                                                {{ Str::limit($column['custom_url'] ?? 'URL', 50) }}
                                                                                            @endif
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="text-muted small">No link</span>
                                                                                    @endif

                                                                                    <button type="button" class="btn btn-sm btn-primary"
                                                                                            wire:click="openLinkModal({{ $sectionIndex }}, {{ $rowIndex }}, {{ $columnIndex }})">
                                                                                        <i class="fas fa-link"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    @elseif (isset($section['columns']))
                                                        <!-- Backward compatibility with old structure -->
                                                        <div class="row">
                                                            @foreach ($section['columns'] as $columnIndex => $column)
                                                                @php
                                                                    $colClass = match($section['type']) {
                                                                        '2_columns' => 'col-md-6',
                                                                        '3_columns' => 'col-md-4',
                                                                        '4_columns' => 'col-md-3',
                                                                        default => 'col-md-12'
                                                                    };
                                                                @endphp
                                                                <div class="{{ $colClass }} mb-3">
                                                                    @if (!empty($column['image']))
                                                                        <div class="position-relative">
                                                                            <img src="{{ $column['image'] }}"
                                                                                class="img-fluid w-100" alt="Column Image"
                                                                                style="height: 200px; object-fit: cover;">
                                                                            <div class="position-absolute bottom-0 end-0 p-2 d-flex align-items-center">
                                                                                <button class="btn btn-sm btn-primary mr-2"
                                                                                        wire:click="openImageModal({{ $sectionIndex }}, null, {{ $columnIndex }})">
                                                                                    <i class="fas fa-image"></i>
                                                                                </button>

                                                                                @if(isset($column['link_type']) && $column['link_type'] !== 'none')
                                                                                    <span class="text-dark small bg-white px-2 py-1 rounded">
                                                                                        @if($column['link_type'] === 'category')
                                                                                            @php
                                                                                                $category = $categories->firstWhere('id', $column['category_id']);
                                                                                            @endphp
                                                                                            {{ $category ? $category->name : 'Category' }}
                                                                                        @else
                                                                                            {{ Str::limit($column['custom_url'] ?? 'URL', 20) }}
                                                                                        @endif
                                                                                    </span>
                                                                                @else
                                                                                    <span class="text-muted small bg-white px-2 py-1 rounded">No link</span>
                                                                                @endif

                                                                                <button class="btn btn-sm btn-primary ml-2"
                                                                                        wire:click="openLinkModal({{ $sectionIndex }}, null, {{ $columnIndex }})">
                                                                                    <i class="fas fa-link"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="text-center py-5 border"
                                                                            wire:click="openImageModal({{ $sectionIndex }}, null, {{ $columnIndex }})">
                                                                            <p class="text-muted">Click to add an image</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Update Page</button>
                </div>
            </div>
        </form>
    </section>

    <!-- Section Type Modal -->
    <div class="modal fade" id="sectionModal" tabindex="-1" role="dialog" aria-labelledby="sectionModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sectionModalLabel">
                        {{ $editingSectionIndex !== null ? 'Edit Section' : 'Add New Section' }}
                    </h5>
                    <button type="button" class="close" wire:click="closeSectionModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sectionName">Section Name</label>
                        <input type="text" class="form-control" id="sectionName" wire:model.live="sectionName" placeholder="Enter a descriptive name for this section">
                    </div>
                    <div class="form-group mt-4">
                        <label class="d-block mb-3">Select Layout</label>

                        <div class="row layout-selector">
                            <!-- Full Width Slider -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === 'full_width_slider' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('full_width_slider')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <rect x="10" y="20" width="280" height="40" fill="#adb5bd" />
                                                <circle cx="150" cy="70" r="3" fill="#495057" />
                                                <circle cx="160" cy="70" r="3" fill="#495057" />
                                                <circle cx="140" cy="70" r="3" fill="#495057" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Full Width Image -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === 'full_width_image' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('full_width_image')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <rect x="10" y="10" width="280" height="60" fill="#adb5bd" />
                                                <path d="M150,30 L170,50 L130,50 Z" fill="#6c757d" />
                                                <circle cx="180" cy="25" r="8" fill="#6c757d" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2 Columns (6x6) -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '6x6_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('6x6_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="150" y1="0" x2="150" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="10" y="10" width="130" height="60" fill="#adb5bd" />
                                                <rect x="160" y="10" width="130" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3 Columns (4x4x4) -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '4x4x4_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('4x4x4_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="100" y1="0" x2="100" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="200" y1="0" x2="200" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="10" y="10" width="80" height="60" fill="#adb5bd" />
                                                <rect x="110" y="10" width="80" height="60" fill="#adb5bd" />
                                                <rect x="210" y="10" width="80" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4 Columns (3x3x3x3) -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '3x3x3x3_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('3x3x3x3_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="75" y1="0" x2="75" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="150" y1="0" x2="150" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="225" y1="0" x2="225" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="8" y="10" width="60" height="60" fill="#adb5bd" />
                                                <rect x="83" y="10" width="60" height="60" fill="#adb5bd" />
                                                <rect x="158" y="10" width="60" height="60" fill="#adb5bd" />
                                                <rect x="233" y="10" width="60" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Left Sidebar (3x9) -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '3x9_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('3x9_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="75" y1="0" x2="75" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="10" y="10" width="55" height="60" fill="#adb5bd" />
                                                <rect x="85" y="10" width="205" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Sidebar (9x3) -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '9x3_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('9x3_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="225" y1="0" x2="225" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="10" y="10" width="205" height="60" fill="#adb5bd" />
                                                <rect x="235" y="10" width="55" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 6x3x3 Columns -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '6x3x3_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('6x3x3_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="150" y1="0" x2="150" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="225" y1="0" x2="225" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="10" y="10" width="130" height="60" fill="#adb5bd" />
                                                <rect x="160" y="10" width="55" height="60" fill="#adb5bd" />
                                                <rect x="235" y="10" width="55" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3x3x6 Columns -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '3x3x6_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('3x3x6_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="75" y1="0" x2="75" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="150" y1="0" x2="150" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="10" y="10" width="55" height="60" fill="#adb5bd" />
                                                <rect x="85" y="10" width="55" height="60" fill="#adb5bd" />
                                                <rect x="160" y="10" width="130" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3x6x3 Columns -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '3x6x3_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('3x6x3_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="75" y1="0" x2="75" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="225" y1="0" x2="225" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="10" y="10" width="55" height="60" fill="#adb5bd" />
                                                <rect x="85" y="10" width="130" height="60" fill="#adb5bd" />
                                                <rect x="235" y="10" width="55" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 6x2x2x2 Columns -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '6x2x2x2_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('6x2x2x2_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="150" y1="0" x2="150" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="200" y1="0" x2="200" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="250" y1="0" x2="250" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="10" y="10" width="130" height="60" fill="#adb5bd" />
                                                <rect x="160" y="10" width="30" height="60" fill="#adb5bd" />
                                                <rect x="210" y="10" width="30" height="60" fill="#adb5bd" />
                                                <rect x="260" y="10" width="30" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2x2x2x6 Columns -->
                            <div class="col-md-3 mb-4">
                                <div class="card layout-card {{ $sectionType === '2x2x2x6_columns' ? 'selected' : '' }}"
                                     wire:click="selectSectionType('2x2x2x6_columns')">
                                    <div class="card-body p-2 text-center">
                                        <div class="layout-preview">
                                            <svg width="100%" height="80" viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="300" height="80" fill="#e9ecef" />
                                                <line x1="50" y1="0" x2="50" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="100" y1="0" x2="100" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <line x1="150" y1="0" x2="150" y2="80" stroke="#ced4da" stroke-width="1" stroke-dasharray="4" />
                                                <rect x="10" y="10" width="30" height="60" fill="#adb5bd" />
                                                <rect x="60" y="10" width="30" height="60" fill="#adb5bd" />
                                                <rect x="110" y="10" width="30" height="60" fill="#adb5bd" />
                                                <rect x="160" y="10" width="130" height="60" fill="#adb5bd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeSectionModal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="addSection">Add Section</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Upload Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
         aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Upload Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="image">Select Image</label>
                        <input type="file" class="form-control-file" id="image" wire:model.live="tempImage">
                        @error('tempImage') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if ($tempImage)
                        <div class="mt-3">
                            <img src="{{ $tempImage->temporaryUrl() }}" class="img-fluid" alt="Preview">
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="uploadImage" wire:loading.attr="disabled">
                        <span wire:loading wire:target="uploadImage">Uploading...</span>
                        <span wire:loading.remove wire:target="uploadImage">Upload</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Modal -->
    <div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="linkModalLabel"
         aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="linkModalLabel">Set Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="linkType">Link Type</label>
                        <select class="form-control" id="linkType" wire:model.live="linkType">
                            <option value="none">No Link</option>
                            <option value="category">Category Page</option>
                            <option value="custom">Custom URL</option>
                        </select>
                    </div>

                    @if ($linkType === 'category')
                        <div class="form-group">
                            <label for="categoryId">Select Category</label>
                            <select class="form-control" id="categoryId" wire:model.live="selectedCategoryId">
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif ($linkType === 'custom')
                        <div class="form-group">
                            <label for="customUrl">Custom URL</label>
                            <input type="text" class="form-control" id="customUrl" wire:model.live="customUrl"
                                   placeholder="[https://example.com](https://example.com)">
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="saveLink">Save Link</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .layout-selector .layout-card {
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #dee2e6;
            position: relative;
            overflow: hidden;
        }

        .layout-selector .layout-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-color: #adb5bd;
        }

        .layout-selector .layout-card.selected {
            border: 3px solid #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .layout-selector .layout-card.selected:after {
            content: '✓';
            position: absolute;
            top: -5px;
            right: 5px;
            font-size: 20px;
            color: #000;
            font-weight: bold;
        }

        .layout-preview {
            height: 80px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            margin-bottom: 10px;
            border-radius: 4px;
            overflow: hidden;
        }

        .layout-preview .preview-col {
            height: 80px;
            border-right: 1px dashed #ced4da;
            background-color: #e9ecef;
        }

        .layout-preview .preview-col:last-child {
            border-right: none;
        }

        .slider-preview {
            background-image: linear-gradient(90deg, #e9ecef 70%, #dee2e6 70%, #dee2e6 75%, #e9ecef 75%);
        }

        .full-width-preview {
            background-color: #e9ecef;
        }

        /* Consistent image heights */
        .card-img-top,
        .position-relative img.img-fluid.w-100,
        .full-width-image-section img.img-fluid {
            height: 150px;
            object-fit: cover;
            width: 100%;
        }

        .card-body .img-fluid {
            height: 150px;
            object-fit: cover;
            width: 100%;
        }

        /* Make card heights consistent */
        .card.h-100 {
            height: 100%;
        }
    </style>
@endpush

@push('scripts')
        <script>
            window.addEventListener('livewire:init', function () {
                Livewire.on('showSectionModal', function () {
                    $('#sectionModal').modal('show');
                });

                Livewire.on('showImageModal', function () {
                    $('#imageModal').modal('show');
                });

                Livewire.on('showLinkModal', function () {
                    $('#linkModal').modal('show');
                });
            });

            Livewire.on('showSectionModal', () => {
                $('#sectionModal').modal('show');
            });

            Livewire.on('hideSectionModal', () => {
                $('#sectionModal').modal('hide');
            });

            Livewire.on('showImageModal', () => {
                $('#imageModal').modal('show');
            });

            Livewire.on('hideImageModal', () => {
                $('#imageModal').modal('hide');
            });

            Livewire.on('showLinkModal', () => {
                $('#linkModal').modal('show');
            });

            Livewire.on('hideLinkModal', () => {
                $('#linkModal').modal('hide');
            });
        </script>
    @endpush
</div>

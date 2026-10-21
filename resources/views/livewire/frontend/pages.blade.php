<div>
    <livewire:frontend.components.header.search.container/>
    <br/><br/>
    @foreach($content as $section)
        @if($section['type'] === 'full_width_slider')
            <section class="destination-area section--padding">
                <div class="container mb-5">
                <div class="row">
                    <div class="col-12" style="overflow: hidden;">
                        @if(!empty($section['name']))
                            <div class="section-heading">
                                <h2 class="sec__title_left left-line" style="font-size:22px">
                                    <h4 class="mb-4">{{ $section['name'] }}</h4></h2>
                            </div>
                        @endif
                        <div class="swiper-container swiper-slider-{{ $loop->index }}">
                            <div class="swiper-wrapper">
                                @foreach($section['slides'] as $slideIndex => $slide)
                                    <div class="swiper-slide" data-link="{{
                                        (isset($slide['link_type']) && $slide['link_type'] === 'category' && isset($slide['category_slug']))
                                            ? '/v2/frontend/category/' . $slide['category_slug']
                                            : ((isset($slide['link_type']) && $slide['link_type'] === 'custom' && !empty($slide['custom_url']))
                                                ? $slide['custom_url']
                                                : '')
                                    }}">
                                        <div class="slider-image-container">
                                            <div class="image-link{{ (isset($slide['link_type']) && $slide['link_type'] === 'none') ? ' no-link' : '' }}">
                                                <img src="{{ $slide['image'] }}" alt="Slide {{ $slideIndex + 1 }}">
                                                @if(isset($slide['link_type']) && $slide['link_type'] === 'category' && isset($slide['category_show_title']) && $slide['category_show_title'] == 1)
                                                    <div class="slider-caption">
                                                        <span class="btn btn-primary">{{ $slide['category_name'] ?? 'View' }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(count($section['slides']) > 1)
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            </section>
            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const swiper{{ $loop->index }} = new Swiper('.swiper-slider-{{ $loop->index }}', {
                            loop: true,
                            autoplay: {
                                delay: 5000,
                                disableOnInteraction: false,
                            },
                            pagination: {
                                el: '.swiper-pagination',
                                clickable: true,
                            },
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                            // Add this to prevent Swiper from capturing all click events
                            touchStartPreventDefault: false,
                            simulateTouch: false,
                            preventClicks: false,
                            preventClicksPropagation: false,
                            slideToClickedSlide: false
                        });

                        // Add click event listeners to the slides
                        const slides{{ $loop->index }} = document.querySelectorAll('.swiper-slider-{{ $loop->index }} .swiper-slide');
                        slides{{ $loop->index }}.forEach(function(slide) {
                            slide.addEventListener('click', function(e) {
                                const link = slide.getAttribute('data-link');
                                console.log('Slide clicked, data-link:', link);

                                // For testing, let's make all slides clickable
                                if (link && link !== '') {
                                    // Stop autoplay when a link is clicked
                                    swiper{{ $loop->index }}.autoplay.stop();
                                    // Navigate to the link
                                    window.location.href = link;
                                } else {
                                    // For slides with no link, let's navigate to a default page
                                    // Uncomment this if you want all slides to be clickable
                                    // window.location.href = '/default-page';
                                }
                            });
                        });
                    });
                </script>
            @endpush
        @elseif($section['type'] === 'full_width_image')
            <section class="destination-area section--padding">
                <div class="container mb-5">
                    <div class="row">
                        <div class="col-12 px-0 position-relative">
                            @if(!empty($section['name']))
                                <div class="section-heading">
                                    <h2 class="sec__title_left left-line" style="font-size:22px"><h4 class="text-center mb-4">{{ $section['name'] }}</h4></h2>
                                </div>
                            @endif
                            @if(isset($section['link_type']) && $section['link_type'] !== 'none')
                                @if($section['link_type'] === 'category')
                                    <a href="/v2/frontend/category/{{ $section['category_slug'] ?? '' }}" class="image-link">
                                        <img src="{{ $section['image'] }}" class="img-fluid w-100" alt="Full Width Image">
                                        @if(isset($section['category_show_title']) && $section['category_show_title'] == 1)
                                            <div class="image-caption">
                                                <span class="btn btn-primary">{{ $section['category_name'] ?? 'View Category' }}</span>
                                            </div>
                                        @endif
                                    </a>
                                @else
                                    <a href="{{ $section['custom_url'] }}" class="image-link">
                                        <img src="{{ $section['image'] }}" class="img-fluid w-100" alt="Full Width Image">
                                        <div class="image-caption">
                                            <span class="btn btn-primary">View</span>
                                        </div>
                                    </a>
                                @endif
                            @else
                                <img src="{{ $section['image'] }}" class="img-fluid w-100" alt="Full Width Image">
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @elseif(in_array($section['type'], ['2_columns', '3_columns', '4_columns', '6x6_columns', '4x4x4_columns', '3x3x3x3_columns', '3x9_columns', '9x3_columns', '6x3x3_columns', '3x3x6_columns', '3x6x3_columns', '6x2x2x2_columns', '2x2x2x6_columns']))
            <section class="destination-area section--padding">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            @if(!empty($section['name']))
                                <div class="section-heading">
                                    <h2 class="sec__title_left left-line" style="font-size:22px">{{ $section['name'] }}</h2>
                                </div><!-- end section-heading -->
                            @endif
                        </div><!-- end col-lg-12 -->
                    </div><!-- end row -->
                    
                    @if(isset($section['rows']))
                        <!-- Multi-row structure -->
                        @foreach($section['rows'] as $row)
                            <div class="row padding-top-50px">
                                @foreach($row as $column)
                                    <div class="col-md-{{ $column['size'] }}">
                                        @if(!empty($column['image']))
                                            <a href="{{ 
                                                (isset($column['link_type']) && $column['link_type'] === 'category') 
                                                    ? '/v2/frontend/category/' . ($column['category_slug'] ?? '') 
                                                    : ((isset($column['link_type']) && $column['link_type'] === 'custom' && !empty($column['custom_url'])) 
                                                        ? $column['custom_url'] 
                                                        : '#') 
                                                }}" 
                                                class="waves-effect {{ (isset($column['link_type']) && $column['link_type'] === 'none') ? 'no-link' : '' }}">
                                                <div class="card-item destination-card">
                                                    <div class="card-img">
                                                        <img src="{{ $column['image'] }}" class="lazyloaded" alt="destination-img">
                                                    </div>
                                                    @if(isset($column['category_show_title']) && $column['category_show_title'] == 1)
                                                        <div class="card-body mb-1">
                                                            <h3 class="card-title">{{ $column['category_name'] ?? 'View' }}</h3>
                                                            <div class="card-rating d-flex align-items-center"></div>
                                                            <div class="card-price d-flex align-items-center justify-content-between">
                                                                <p class="tour__text"></p>
                                                                <p><span class="price__from"></span><span class="price__num"><strong></strong></span></p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </a>
                                        @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @elseif(isset($section['columns']))
                    <!-- Legacy column structure -->
                    <div class="row padding-top-50px">
                        @foreach($section['columns'] as $column)
                            @php
                                $colClass = match($section['type']) {
                                    '2_columns' => 'col-md-6',
                                    '3_columns' => 'col-md-4',
                                    '4_columns' => 'col-md-3',
                                    default => 'col-md-12'
                                };
                            @endphp
                            <div class="{{ $colClass }}">
                                @if(!empty($column['image']))
                                    <a href="{{ 
                                        (isset($column['link_type']) && $column['link_type'] === 'category') 
                                            ? '/v2/frontend/category/' . ($column['category_slug'] ?? '') 
                                            : ((isset($column['link_type']) && $column['link_type'] === 'custom' && !empty($column['custom_url'])) 
                                                ? $column['custom_url'] 
                                                : '#') 
                                        }}" 
                                        class="waves-effect {{ (isset($column['link_type']) && $column['link_type'] === 'none') ? 'no-link' : '' }}">
                                        <div class="card-item destination-card">
                                            <div class="card-img">
                                                <img src="{{ $column['image'] }}" class="lazyloaded" alt="destination-img">
                                            </div>
                                            @if(isset($column['category_show_title']) && $column['category_show_title'] == 1)
                                                <div class="card-body mb-1">
                                                    <h3 class="card-title">{{ $column['category_name'] ?? 'View' }}</h3>
                                                    <div class="card-rating d-flex align-items-center"></div>
                                                    <div class="card-price d-flex align-items-center justify-content-between">
                                                        <p class="tour__text"></p>
                                                        <p><span class="price__from"></span><span class="price__num"><strong></strong></span></p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            </section>
        @endif
    @endforeach

    @push('styles')
        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>

        <style>
            /* Slider Styles */
            .swiper-container {
                max-width: 100%;
                height: 500px;
                overflow: hidden;
                position: relative;
            }

            .swiper-slide {
                text-align: center;
                background: #f8f9fa;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            /* Card image styles for consistent heights */
            .card-item .card-img {
                height: 250px; /* Fixed height for all card images */
                overflow: hidden;
            }
            
            .card-item .card-img img {
                width: 100%;
                height: 100%;
                object-fit: cover; /* Cover the container while maintaining aspect ratio */
                transition: transform 0.3s ease;
            }
            
            .card-item:hover .card-img img {
                transform: scale(1.05); /* Slight zoom effect on hover */
            }

            .slider-image-container {
                width: 100%;
                height: 100%;
                position: relative;
            }

            .slider-image-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .slider-caption {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 20px;
                background-color: rgba(0, 0, 0, 0.5);
                color: white;
                text-align: center;
            }

            /* Navigation buttons */
            .swiper-button-next, .swiper-button-prev {
                color: #fff;
                background: rgba(0, 0, 0, 0.5);
                width: 40px;
                height: 40px;
                border-radius: 50%;
                --swiper-navigation-size: 20px;
                margin-top: -20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .swiper-button-next {
                right: 10px;
            }

            .swiper-button-prev {
                left: 10px;
            }

            .swiper-button-next:hover, .swiper-button-prev:hover {
                background: rgba(0, 0, 0, 0.7);
            }

            /* Pagination bullets */
            .swiper-pagination-bullet {
                width: 12px;
                height: 12px;
                background: #fff;
                opacity: 0.7;
            }

            .swiper-pagination-bullet-active {
                opacity: 1;
                background: #007bff;
            }

            /* Other captions */
            .image-caption, .column-caption {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 20px;
                background-color: rgba(0, 0, 0, 0.5);
                color: white;
                text-align: center;
            }

            /* Image links */
            .image-link {
                display: block;
                position: relative;
                cursor: pointer;
                transition: opacity 0.3s ease;
                z-index: 10;
            }
            .image-link:hover {
                opacity: 0.95;
            }
            .image-link.no-link {
                cursor: default;
            }
            .image-link.no-link:hover {
                opacity: 1;
            }

            /* Make sure the swiper controls don't block links */
            .swiper-button-next, .swiper-button-prev {
                z-index: 20;
            }

            .swiper-container {
                z-index: 1;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Swiper JS -->
        <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    @endpush
</div>

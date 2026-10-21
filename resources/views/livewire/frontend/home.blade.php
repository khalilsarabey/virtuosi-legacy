<div>
    <livewire:frontend.components.header.search.container />
    <br/><br/>
    @foreach($sections as $section)
        @if($section->type == 'images')
                <section class="destination-area section--padding">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <div class="section-heading">
                                    <h2 class="sec__title_left left-line"
                                        style="font-size:22px">{{ $section->name }}</h2>
                                </div><!-- end section-heading -->
                            </div><!-- end col-lg-8 -->
                        </div><!-- end row -->
                        <div class="row padding-top-50px">
                            @foreach($section->categories as $category)
                                <div class="col-md-{{ 12 / $section->layout }}">
                                    <a href="{{ route('frontend.categories.show', $category->slug) }}"
                                       class="waves-effect">
                                        <div class="card-item destination-card">
                                            <div class="card-img">
                                                @foreach($category->image as $key => $entry)
                                                    <img data-src="{{ $entry['thumbnail'] }}"
                                                         class=" ls-is-cached lazyloaded" alt="destination-img"
                                                         src="{{ config('app.image_url') . $entry['url'] }}">
                                                @endforeach
                                            </div>
                                            @if($category->show_title == 1)
                                                <div class="card-body mb-1">
                                                    <h3 class="card-title">{{ $category->name }}</h3>
                                                    <div class="card-rating d-flex align-items-center">
                                                    </div>
                                                    <div
                                                        class="card-price d-flex align-items-center justify-content-between">
                                                        <p class="tour__text"> <!----></p>
                                                        <p>
                                                            <span class="price__from"></span>
                                                            <span class="price__num"><strong></strong></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div><!-- end row -->
                    </div><!-- end container -->
                </section>
            @endif
        @if($section->type == 'linked_images')
                <section class="destination-area section--padding">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <div class="section-heading">
                                    <h2 class="sec__title_left left-line"
                                        style="font-size:22px">{{ $section->name }}</h2>
                                </div><!-- end section-heading -->
                            </div><!-- end col-lg-8 -->
                        </div><!-- end row -->
                        <div class="row padding-top-50px">
                            @foreach($section->categories as $category)
                                <div class="col-md-{{ 12 / $section->layout }}">
                                    <a  @if(!empty($category->link)) target="_blank" href="{{ $category->link }}" @endif class="waves-effect">
                                        <div class="card-item destination-card">
                                            <div class="card-img">
                                                @foreach($category->image as $key => $entry)
                                                    <img data-src="{{ config('app.image_url') . $entry['thumbnail'] }}" class=" ls-is-cached lazyloaded" alt="destination-img" src="{{ $entry['url'] }}">
                                                @endforeach
                                            </div>
                                            @if($category->show_title == 1)
                                                <div class="card-body mb-1">
                                                    <h3 class="card-title">{{ $category->name }}</h3>
                                                    <div class="card-rating d-flex align-items-center"></div>
                                                    <div class="card-price d-flex align-items-center justify-content-between">
                                                        <p class="tour__text"></p>
                                                        <p><span class="price__from"></span><span class="price__num"><strong></strong></span></p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div><!-- end row -->
                    </div><!-- end container -->
                </section>
            @endif
    @endforeach
    @foreach($blocks as $section)
        <section class="destination-area section--padding">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="section-heading">
                            <h2 class="sec__title_left left-line" style="font-size:22px">{{ $section->name }}</h2>
                        </div><!-- end section-heading -->
                    </div><!-- end col-lg-8 -->
                </div><!-- end row -->
                <div class="row padding-top-50px">
                    @foreach($section->children as $category)
                        <div class="col-md-{{ 12 / $section->layout }}">
                                <a @if(!empty($category->link)) target="_blank" href="{{ $category->link }}" @endif class="waves-effect">
                                    <div class="card-item destination-card">
                                        <div class="card-img">
                                            @foreach($category->thumbnail as $key => $entry)
                                                <img data-src="{{ config('app.image_url') . $entry['thumbnail'] }}"
                                                     class=" ls-is-cached lazyloaded" alt="destination-img"
                                                     src="{{ config('app.image_url') . $entry['url'] }}">
                                            @endforeach
                                        </div>
                                        @if($category->hide_title != 1)
                                            <div class="card-body mb-1">
                                                <h3 class="card-title">{{ $category->name }}</h3>
                                                <div class="card-rating d-flex align-items-center"></div>
                                                <div class="card-price d-flex align-items-center justify-content-between">
                                                    <p class="tour__text"> <!----></p>
                                                    <p>
                                                        <span class="price__from"></span>
                                                        <span class="price__num"><strong></strong></span>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                        </div>
                    @endforeach
                </div><!-- end row -->
            </div><!-- end container -->
        </section>
    @endforeach
</div>

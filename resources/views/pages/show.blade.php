@section('content')
    @foreach($content as $section)
        @if($section['type'] === 'full_width_slider')
            <section class="full-width-slider">
                <div id="slider-{{ $loop->index }}" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($section['slides'] as $slideIndex => $slide)
                            <div class="carousel-item {{ $slideIndex === 0 ? 'active' : '' }}">
                                @if($slide['link_type'] === 'none')
                                    <img src="{{ $slide['image'] }}" class="d-block w-100" alt="Slide Image">
                                @elseif($slide['link_type'] === 'custom')
                                    <a href="{{ $slide['custom_url'] }}" target="_blank">
                                        <img src="{{ $slide['image'] }}" class="d-block w-100" alt="Slide Image">
                                    </a>
                                @elseif($slide['link_type'] === 'category' && isset($slide['category_slug']))
                                    <a href="{{ route('category.show', $slide['category_slug']) }}">
                                        <img src="{{ $slide['image'] }}" class="d-block w-100" alt="Slide Image">
                                        @if(isset($slide['category_name']))
                                            <div class="carousel-caption d-none d-md-block">
                                                <h5>{{ $slide['category_name'] }}</h5>
                                            </div>
                                        @endif
                                    </a>
                                @else
                                    <img src="{{ $slide['image'] }}" class="d-block w-100" alt="Slide Image">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if(count($section['slides']) > 1)
                        <a class="carousel-control-prev" href="#slider-{{ $loop->index }}" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#slider-{{ $loop->index }}" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    @endif
                </div>
            </section>
        @elseif($section['type'] === 'full_width_image')
            <section class="full-width-image">
                @if($section['link_type'] === 'none')
                    <img src="{{ $section['image'] }}" class="img-fluid w-100" alt="Full Width Image">
                @elseif($section['link_type'] === 'custom')
                    <a href="{{ $section['custom_url'] }}" target="_blank">
                        <img src="{{ $section['image'] }}" class="img-fluid w-100" alt="Full Width Image">
                    </a>
                @elseif($section['link_type'] === 'category' && isset($section['category_slug']))
                    <a href="{{ route('category.show', $section['category_slug']) }}">
                        <img src="{{ $section['image'] }}" class="img-fluid w-100" alt="Full Width Image">
                        @if(isset($section['category_name']))
                            <div class="image-caption">
                                <h5>{{ $section['category_name'] }}</h5>
                            </div>
                        @endif
                    </a>
                @else
                    <img src="{{ $section['image'] }}" class="img-fluid w-100" alt="Full Width Image">
                @endif
            </section>
        @elseif(in_array($section['type'], ['2_columns', '3_columns', '4_columns']))
            <section class="columns-section container my-5">
                @if(isset($section['rows']))
                    @foreach($section['rows'] as $row)
                        <div class="row mb-4">
                            @foreach($row as $column)
                                @php
                                    $colClass = match($section['type']) {
                                        '2_columns' => 'col-md-6',
                                        '3_columns' => 'col-md-4',
                                        '4_columns' => 'col-md-3',
                                        default => 'col-md-12'
                                    };
                                @endphp
                                <div class="{{ $colClass }} mb-3">
                                    @if($column['link_type'] === 'none')
                                        <img src="{{ $column['image'] }}" class="img-fluid w-100" alt="Column Image">
                                    @elseif($column['link_type'] === 'custom')
                                        <a href="{{ $column['custom_url'] }}" target="_blank">
                                            <img src="{{ $column['image'] }}" class="img-fluid w-100" alt="Column Image">
                                        </a>
                                    @elseif($column['link_type'] === 'category' && isset($column['category_slug']))
                                        <a href="{{ route('category.show', $column['category_slug']) }}">
                                            <img src="{{ $column['image'] }}" class="img-fluid w-100" alt="Column Image">
                                            @if(isset($column['category_name']))
                                                <div class="column-caption">
                                                    <h5>{{ $column['category_name'] }}</h5>
                                                </div>
                                            @endif
                                        </a>
                                    @else
                                        <img src="{{ $column['image'] }}" class="img-fluid w-100" alt="Column Image">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @elseif(isset($section['columns']))
                    <div class="row">
                        @foreach($section['columns'] as $column)
                            @php
                                $colClass = match($section['type']) {
                                    '2_columns' => 'col-md-6',
                                    '3_columns' => 'col-md-4',
                                    '4_columns' => 'col-md-3',
                                    default => 'col-md-12'
                                };
                            @endphp
                            <div class="{{ $colClass }} mb-3">
                                @if($column['link_type'] === 'none')
                                    <img src="{{ $column['image'] }}" class="img-fluid w-100" alt="Column Image">
                                @elseif($column['link_type'] === 'custom')
                                    <a href="{{ $column['custom_url'] }}" target="_blank">
                                        <img src="{{ $column['image'] }}" class="img-fluid w-100" alt="Column Image">
                                    </a>
                                @elseif($column['link_type'] === 'category' && isset($column['category_slug']))
                                    <a href="{{ route('category.show', $column['category_slug']) }}">
                                        <img src="{{ $column['image'] }}" class="img-fluid w-100" alt="Column Image">
                                        @if(isset($column['category_name']))
                                            <div class="column-caption">
                                                <h5>{{ $column['category_name'] }}</h5>
                                            </div>
                                        @endif
                                    </a>
                                @else
                                    <img src="{{ $column['image'] }}" class="img-fluid w-100" alt="Column Image">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif
    @endforeach
@endsection

@push('styles')
<style>
    .carousel-item {
        height: 500px;
    }

    .carousel-item img {
        object-fit: cover;
        height: 100%;
    }

    .image-caption, .column-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 10px;
        text-align: center;
    }
</style>
@endpush

<div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" />
    <section class="breadcrumb-area bread-bg-flights mb-5">
        <div class="breadcrumb-wrap">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="breadcrumb-content my-5">
                            <div class="section-heading">
                                <div class="left-side-info" style="color:#fff">
                                    <h2 class="sec__title_list mb-1"
                                        style="font-size: 2rem !important; font-weight: 500 !important">Announcements</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        @if(auth()->user()->agency_id == 1)
            <div class="container">
                @foreach($announcements as $announcement)
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success" role="alert">
                                <h5 class="alert-heading">{{ $announcement->title }}</h5>
                                <div>{!! $announcement->content !!}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-12">
                        {{ $announcements->links() }}
                    </div>
                </div>
            </div>
        @endif
    </section>
</div>

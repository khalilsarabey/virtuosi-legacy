<div>
    <style>
        th.sidebar {
            background: rgb(231 229 228);
            text-align: center;
            color: var(--theme);
            font-size: .9rem
        }
        table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            position: relative;
        }
        table tr:first-child td:first-child {
            background-color: transparent;
            border: none;
        }
        table * {
            position: relative;
        }
        table td, table th {
            padding-left: 8px;
        }
        table thead tr {
            height: 80px;
            background: rgb(231 229 228);
            text-align: center;
            color: var(--theme);
            font-size: .9rem
        }
        table thead tr th {
            text-align: center;
        }
        table tbody tr {
            height: 48px;
            border-bottom: 1px solid #E3F1D5;
        }
        table tbody tr:last-child {
            border: 0;
        }
        table td, table th {
            text-align: left;
        }
        table td.l, table th.l {
            text-align: right;
        }
        table td.c, table th.c {
            text-align: center;
        }
        table td.r, table th.r {
            text-align: center;
        }

        @media screen and (max-width: 35.5em) {
            table {
                display: block;
            }
            table > *, table tr, table td, table th {
                display: block;
            }
            table thead {
                display: none;
            }
            table tbody tr {
                height: auto;
                padding: 8px 0;
            }
            table tbody tr td {
                padding-left: 45%;
                margin-bottom: 12px;
            }
            table tbody tr td:last-child {
                margin-bottom: 0;
            }
            table tbody tr td:before {
                position: absolute;
                font-weight: 700;
                width: 40%;
                left: 10px;
                top: 0;
            }
            table tbody tr td:nth-child(1):before {
                content: "";
            }
            table tbody tr td:nth-child(2):before {
                content: "";
            }
            table tbody tr td:nth-child(3):before {
                content: "";
            }
            table tbody tr td:nth-child(4):before {
                content: "";
            }
            table tbody tr td:nth-child(5):before {
                content: ";
            }
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info d-flex flex-lg-row flex-column justify-content-between align-items-center show mb-5 shadow-sm" role="alert">
                <div>
                    <strong>Explore Flexible Dates!</strong> Find the best prices by comparing flexible date options.
                </div>
                <button type="button" wire:click="toggle" class="btn btn-warning font-weight-bold px-5">Lowest Price Table</button>
            </div>
        </div>
    </div>
    <div class="row {{ $show ? '':'d-none' }}">
            @if(count($grids) > 0)
            <style>
                .vertical-text {
                    writing-mode: vertical-rl;
                    text-orientation: mixed;
                    transform: rotate(180deg); /* Flip text to be readable */
                    display: inline-block;
                }
                .vertical-container {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100%;
                }
            </style>
            <div class="col-auto d-flex align-items-center">
                <div class="vertical-text"><h5>Return</h5></div>
            </div>
            <div class="col">
                <div class="text-center py-4 d-flex align-items-center justify-content-between">
                    <a href="#" wire:click.prevent="previous">
                        <svg fill="{{ $site_settings->primary_color ?? '#000000' }}" height="35px" width="35px" version="1.1" id="back" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" transform="matrix(-1, 0, 0, 1, 0, 0)"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M335.083,271.083 L228.416,377.749c-4.16,4.16-9.621,6.251-15.083,6.251c-5.461,0-10.923-2.091-15.083-6.251c-8.341-8.341-8.341-21.824,0-30.165 L289.835,256l-91.584-91.584c-8.341-8.341-8.341-21.824,0-30.165s21.824-8.341,30.165,0l106.667,106.667 C343.424,249.259,343.424,262.741,335.083,271.083z"></path> </g> </g> </g></svg>
                    </a>
                    <h5>Departure</h5>
                        <a href="#" wire:click.prevent="next">
                            <svg fill="{{ $site_settings->primary_color ?? '#000000' }}" height="35px" width="35px" version="1.1" id="next" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M335.083,271.083
                                        L228.416,377.749c-4.16,4.16-9.621,6.251-15.083,6.251c-5.461,0-10.923-2.091-15.083-6.251c-8.341-8.341-8.341-21.824,0-30.165
                                        L289.835,256l-91.584-91.584c-8.341-8.341-8.341-21.824,0-30.165s21.824-8.341,30.165,0l106.667,106.667
                                        C343.424,249.259,343.424,262.741,335.083,271.083z"/>
                                </g>
                            </g>
                            </svg>
                        </a>
                </div>
                <table class="mb-3 styled-table">
                    <thead>
                    <tr>
                        <th style="background-color: {{ $site_settings->color_body_bg ?? '#F0F0F0' }}"></th>
                        @foreach($this->departuresHeader as $header)
                            <th>
                                {{  \Carbon\Carbon::parse($header)->format('l') }}<br/>
                                {{  \Carbon\Carbon::parse($header)->format('d M, y') }}
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="bg-white" >
                    <tr>
                        @php $day = 1 @endphp
                        @foreach($grids as $k => $grid)
                            @if($day == 1)
                                <th class="sidebar">
                                    @if($grid['return']['dep_date'] ?? null)
                                        {{  \Carbon\Carbon::parse($grid['return']['dep_date'])->format('l') }}<br/>
                                        {{  \Carbon\Carbon::parse($grid['return']['dep_date'])->format('d M, y') }}
                                    @endif
                                </th>
                            @endif
                            @if(isset($grid['prices']) && $grid['prices'] > 0)
                                <td @if($foundResultsLowestPrices['total'] > $grid['prices']['total']) style="background: rgba(254, 249, 195, .3)" @endif>
                                    @php
                                        $link = $query;
                                        $link['dep_date'] = $grid['departure']['dep_date'];
                                        $link['ret_date'] = $grid['return']['dep_date'];
                                    @endphp
                                    <a href="/v2/frontend/search?{{ http_build_query($link) }}" class="d-block py-3">
                                        @if($foundResultsLowestPrices['total'] > $grid['prices']['total'])
                                            <div class="text-center font-weight-bold  text-danger">${{ $foundResultsLowestPrices['total'] }}</div>
                                            <div class="font-weight-bold text-center" style="font-size: 12px;">You Save <br/>${{ $foundResultsLowestPrices['total'] - $grid['prices']['total'] }}</div>
                                        @else
                                            <div class="text-center font-weight-bold  text-dark">${{ $foundResultsLowestPrices['total'] }}</div>
                                        @endif
                                    </a>
                                </td>
                            @else
                                <td class="text-center">
                                    <small class="text-muted">Not Available</small>
                                </td>
                            @endif
                            @if($day == 7)
                                @php $day = 1 @endphp
                    </tr>
                    <tr>
                        @else
                            @php $day++ @endphp
                        @endif
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
            @else
                <div class="col-12">
                    <div class="alert alert-info text-center mb-3 w-100">Flexible dates grid is not available for <strong>{{ $query['dep_from_code'] }} -> {{ $query['dep_to_code'] }}</strong> at the moment.</div>
                </div>
            @endif
    </div>
</div>

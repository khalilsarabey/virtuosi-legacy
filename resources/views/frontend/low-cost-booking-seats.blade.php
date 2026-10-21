@extends('layouts.blade')
@section('content')
    @if(isset($airline))
        @php $c = "frontend.low-cost.". str_replace('"', '', $airline) . ".seats" @endphp
        @livewire($c, ['data' => $data])
    @else
        @php $c = "frontend.low-cost.air-arabia.seats" @endphp
        @livewire($c, ['data' => []])
    @endif
@endsection

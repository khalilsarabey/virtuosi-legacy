@extends('layouts.blade')
@section('content')
    @php $c = "frontend.low-cost.". str_replace('"', '', $airline) . ".booking" @endphp
    @livewire($c, ['data' => $data])
@endsection

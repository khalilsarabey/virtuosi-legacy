@extends('layouts.auth')

@section('content')
    <section id="hero" class="login vh-100">
        <div class="container vh-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
                    @if(session('status'))
                        <div class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-green-500">
                            <span class="inline-block align-middle mr-8">
                                {{ session('status') }}
                            </span>
                        </div>
                    @endif
                    <div id="login" class="bg-white p-5 shadow" style="border-top-right-radius: 2rem; border-bottom-left-radius: 2rem;">
                        <div class="text-center">
                            @if($site_settings->logo->first())
                                <img src="{{ $site_settings->logo->first()['url'] }}" alt="Image" class="img-fluid px-5" style="max-height: 50px">
                            @else
                                <h5>{{ $site_settings->name }}</h5>
                            @endif
                        </div>
                        <hr>
                        <form method="post" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class=" form-control " required="" placeholder="" autocomplete="email" autofocus="" name="email" value="">
                            </div>
                            @error('email')
                            <div class="text-red-500">
                                <small>{{ $message }}</small>
                            </div>
                            @enderror
                            <button class="btn btn-primary w-100 my-3" type="submit">
                                Reset Password
                            </button>
                            <p class="small text-end">
                                <a class="text-muted text-end" href="{{ route('login') }}">Back to Login</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

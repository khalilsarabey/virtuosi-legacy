<div>
    <section id="hero" class="login vh-100">
        <div class="container vh-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xl-5 col-lg-5 col-md-6 col-sm-8">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
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
                        <form method="post">
                                @csrf
                                <div class="form-group mt-3">
                                    <label>Enter Code</label>
                                    <input type="text" class="form-control" placeholder="" required autocomplete="current-password" name="password">
                                    <div class="text-muted mt-3" style="font-size: 14px">We have sent you a one time password to your email.</div>
                                    <div class=" text-muted" style="font-size: 14px" >Please enter the code that was sent to your email.</div>
                                    <div class=" text-muted" style="font-size: 14px" >The code is valid for 10 minutes.</div>
                                    <div class=" text-muted" style="font-size: 14px" >If you didn't receive an email, you can request a new one by clicking below</div>
                                    <hr/>
                                </div>
                                @error('password')
                                <div class="text-red-500">
                                    <small>{{ $message }}</small>
                                </div>
                                @enderror
                                <button class="btn btn-primary w-100 my-3" type="submit">
                                    Submit
                                </button>
                                <div class="text-center w-100">
                                    <a class="text-success"  style="font-size: 14px" href="#">Request new Code</a>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

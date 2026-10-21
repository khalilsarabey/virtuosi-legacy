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
                        @if($success)
                            <div class="alert alert-success my-4">Your password has been changed</div>
                            <div class="my-4 text-center"><a class="btn btn-secondary" href="/">Continue to your account</a></div>
                        @else
                            <form method="post">
                                @csrf
                                <div class="form-group mt-3">
                                    <label>Change your password</label>
                                    <input type="text" class="form-control" wire:model="password" placeholder="Password" required autocomplete="current-password" name="password">
                                    <progress value="{{ $strengthScore }}" max="4" class="w-100"></progress>
                                    @if($password)
                                        @if($strengthScore > 2)
                                            <div class="text-success"><small>{{ $strengthLevels[$strengthScore] }}</small></div>
                                        @else
                                            <div class="text-danger"><small>{{ $strengthLevels[$strengthScore] }}</small></div>
                                        @endif
                                    @endif
                                    <div class="mt-3 border-bottom-v=" style="font-size: 14px; border-bottom:1px solid #dee2e6; padding-bottom: 5px; margin-bottom: 5px;">Password Tips</div>
                                    <ul style="font-size: 13px;">
                                        <li>- Use 6 or more characters</li>
                                        <li>- Include uppercase and lowercase letters</li>
                                        <li>- Include numbers</li>
                                        <li>- Include symbols</li>
                                        <li>- Don't use common passwords</li>
                                    </ul>
                                    <div class="mt-3" style="font-size: 14px;">
                                        If you can't think of a good password<br/><div style="cursor: pointer; text-decoration: underline" class="text-primary border-0 px-0 underline" wire:click="generatePassword">Click here and we'll generate one for you.</div>
                                    </div>
                                </div>
                                @error('password')
                                <div class="text-red-500">
                                    <small>{{ $message }}</small>
                                </div>
                                @enderror
                                <button wire:click="save" class="btn btn-primary w-100 my-3" type="button" {{ $strengthScore < 3 ? 'disabled' : '' }}>
                                    Save new Password
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

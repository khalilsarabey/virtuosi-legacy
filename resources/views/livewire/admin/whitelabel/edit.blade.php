<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Edit White Label
                    @if($errors->any())
                        {{ implode('', $errors->all('<div>:message</div>')) }}
                    @endif
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid">
            <form wire:submit.prevent="save" class="row">
                <div class="col-12">
                    <label class="my-4" style="font-size:1rem">AGENCY DETAILS</label>
                    <hr/>
                </div>
                <div class="col-md-3">
                    <label>Domain Name</label>
                    <input type="text" name="name" class="form-control" wire:model.defer="agency.domain" required />
                    @error('agency.domain') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label>Agency Name</label>
                    <input type="text" name="name" class="form-control" wire:model.defer="agency.name" required />
                    @error('agency.name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label>Balance</label>
                    <input type="number" name="balance" class="form-control" wire:model.defer="agency.balance" required />
                    @error('agency.balance') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="{{ $errors->has('agency.email') ? 'text-danger' : '' }}">PLATFORM EMAIL</label>
                    <input wire:model.defer="agency.email" type="email" required name="email" class="form-control"/>
                    <div class="validation-message">
                        {{ $errors->first('agency.email') }}
                    </div>
                </div>
                <div class="col-12">
                    <br/>
                    <hr/>
                    <label class="my-4" style="font-size:1rem">ADMIN ACCOUNT</label>
                    <hr/>
                </div>
                <div class="col-md-4">
                    <label>Name</label>
                    <input type="text" name="user_name" class="form-control" wire:model.defer="user.name" required />
                    @error('user.name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="user_email" class="form-control" wire:model.defer="user.email" required />
                    @error('user.email') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" wire:model.defer="user.password" required />
                    @error('user.password') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="my-5" style="font-size:1rem">BRAND COLORS</label>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="{{ $errors->has('agency.primary_color') ? 'text-danger' : '' }}">PRIMARY COLOR</label>
                            <input wire:model.defer="agency.primary_color" type="color" required name="primary_color" class="form-control"/>
                            <div class="validation-message">
                                {{ $errors->first('agency.primary_color') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">SECONDARY COLOR</label>
                            <input wire:model.defer="agency.secondary_color1" type="color" required name="secondary_color1" class="form-control"/>
                            <div class="validation-message">
                                {{ $errors->first('agency.secondary_color1') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">SUCCESS COLOR</label>
                            <input wire:model.defer="agency.color_success" type="color" required name="color_success" class="form-control"/>
                            <div class="validation-message">
                                {{ $errors->first('agency.color_success') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">INFO COLOR</label>
                            <input wire:model.defer="agency.color_info" type="color" required name="color_info" class="form-control"/>
                            <div class="validation-message">
                                {{ $errors->first('agency.color_info') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">WARNING COLOR</label>
                            <input wire:model.defer="agency.color_warning" type="color" required name="color_warning" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_warning') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">DANGER & ERROR COLOR</label>
                            <input wire:model.defer="agency.color_danger" type="color" required name="color_danger" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_danger') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">LIGHT COLOR</label>
                            <input wire:model.defer="agency.color_light" type="color" required name="color_light" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_light') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">FOOTER BACKGROUND</label>
                            <input wire:model.defer="agency.color_footer_bg" type="color" required name="color_footer_bg" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_footer_bg') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">FOOTER LIGHT BACKGROUND</label>
                            <input wire:model.defer="agency.color_footer_bg_light" type="color" required name="color_footer_bg_light" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_footer_bg_light') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">FOOTER TEXT COLOR</label>
                            <input wire:model.defer="agency.color_footer_text" type="color" required name="color_footer_text" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_footer_text') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">FOOTER ICONS BACKGROUND</label>
                            <input wire:model.defer="agency.color_footer_icons_bg" type="color" required name="color_footer_icons_bg" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_footer_icons_bg') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">FOOTER ICONS COLOR</label>
                            <input wire:model.defer="agency.color_footer_icons_color" type="color" required name="color_footer_icons_color" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_footer_icons_color') }}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label class="">BODY BACKGROUND COLOR</label>
                            <input wire:model.defer="agency.color_body_bg" type="color" required name="color_body_bg" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_body_bg') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="">MENU COLOR</label>
                            <input wire:model.defer="agency.color_top_menu" type="color" required name="color_top_menu" class="form-control" />
                            <div class="validation-message">
                                {{ $errors->first('agency.color_top_menu') }}
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group {{ $errors->has('mediaCollections.agency_logo') ? 'invalid' : '' }}">
                                <label class="form-label required" for="logo">Logo</label>
                                <x-dropzone id="logo" name="logo" action="{{ route('admin.agencies.storeMedia') }}" collection-name="agency_logo" max-file-size="1" max-width="4096" max-height="4096" max-files="1"/>
                                <div class="validation-message text-danger">
                                    {{ $errors->first('mediaCollections.agency_logo') }}
                                </div>
                                <div class="text-danger" style="font-size: .8rem">Max width: 300 pixels</div>
                            </div>
                        </div>
                        <div class="col-md-3" wire:ignore>
                            <div class="form-group {{ $errors->has('mediaCollections.agency_print_logo') ? 'invalid' : '' }}">
                                <label class="form-label required" for="logo">Print Logo</label>
                                <x-dropzone id="print_logo" name="print_logo" action="{{ route('admin.agencies.storeMedia') }}" collection-name="agency_print_logo" max-file-size="1" max-width="4096" max-height="4096" max-files="1"/>
                                <div class="validation-message text-danger">
                                    {{ $errors->first('mediaCollections.agency_print_logo') }}
                                </div>
                                <div class="text-danger" style="font-size: .8rem">Max width: 300 pixels</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group {{ $errors->has('mediaCollections.agency_login_bg') ? 'invalid' : '' }}">
                                <label class="form-label required" for="login_bg">Login Background</label>
                                <x-dropzone id="login_bg" name="login_bg" action="{{ route('admin.agencies.storeMedia') }}"
                                            collection-name="agency_login_bg" max-file-size="1" max-width="4096"
                                            max-height="4096" max-files="1"/>
                                <div class="validation-message text-danger">
                                    {{ $errors->first('mediaCollections.agency_login_bg') }}
                                </div>
                                <div class="text-danger" style="font-size: .8rem">1000 x 700 pixels</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div
                                class="form-group {{ $errors->has('mediaCollections.agency_search_bg') ? 'invalid' : '' }}">
                                <label class="form-label required" for="search_bg">Search Background</label>
                                <x-dropzone id="search_bg" name="search_bg"
                                            action="{{ route('admin.agencies.storeMedia') }}"
                                            collection-name="agency_search_bg" max-file-size="1" max-width="4096"
                                            max-height="800" max-files="1"/>
                                <div class="validation-message text-danger">
                                    {{ $errors->first('mediaCollections.agency_search_bg') }}
                                </div>
                                <div class="text-danger" style="font-size: .8rem">1200 x 810 pixels</div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                </div>
                <div class="col-md-12 d-flex align-items-end justify-content-end">
                    <button type="submit" class="mt-3 btn btn-xs px-4 py-2 btn-danger uppercase waves-effect">Save</button>
                </div>
            </form>
        </div>
    </section>
</div>

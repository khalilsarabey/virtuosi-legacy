<div>
    <div class="admin-header position-relative">
        <div class="my-auto w-100">
        </div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <div class="btn-group" style="border-radius: 10px;" role="group" aria-label="Basic example">
                    <x-admin.settings.my-agency-menu />
                </div>
            </div>
        </div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="container-fluid mb-4">
            <form wire:submit.prevent="submit" class="pt-3">
                <div class="row">
                    <div class="col-12">
                        <div class="text-danger text-center my-5">Do not change anything unless you know what you're doing.</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="{{ $errors->has('agency.mail_host') ? 'text-danger' : '' }}">HOST</label>
                        <input placeholder="ex: mail.mydomain.com" wire:model.defer="agency.mail_host" type="text" required name="mail_host" class="form-control"/>
                        <div class="validation-message">
                            {{ $errors->first('agency.mail_host') }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="{{ $errors->has('agency.mail_port') ? 'text-danger' : '' }}">SMTP PORT</label>
                        <input wire:model.defer="agency.mail_port" type="number" required name="mail_port" class="form-control" />
                        <div class="validation-message">
                            {{ $errors->first('agency.mail_port') }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="{{ $errors->has('agency.mail_encryption') ? 'text-danger' : '' }}">ENCRYPTION</label>
                        <input wire:model.defer="agency.mail_encryption" type="text" required name="mail_encryption" class="form-control"/>
                        <div class="validation-message">
                            {{ $errors->first('agency.mail_encryption') }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="{{ $errors->has('agency.mail_username') ? 'text-danger' : '' }}">USERNAME</label>
                        <input wire:model.defer="agency.mail_username" type="text" required name="mail_username" class="form-control"/>
                        <div class="validation-message">
                            {{ $errors->first('agency.mail_username') }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="{{ $errors->has('agency.mail_password') ? 'text-danger' : '' }}">PASSWORD</label>
                        <input wire:model.defer="agency.mail_password" type="text" required name="mail_password" class="form-control"/>
                        <div class="validation-message">
                            {{ $errors->first('agency.mail_password') }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="{{ $errors->has('agency.mail_sender_name') ? 'text-danger' : '' }}">SENDER NAME</label>
                        <input wire:model.defer="agency.mail_sender_name" type="text" required name="mail_sender_name" class="form-control"/>
                        <div class="validation-message">
                            {{ $errors->first('agency.mail_sender_name') }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="{{ $errors->has('agency.mail_sender_email') ? 'text-danger' : '' }}">SENDER EMAIL</label>
                        <input wire:model.defer="agency.mail_sender_email" type="text" required name="mail_sender_email" class="form-control"/>
                        <div class="validation-message">
                            {{ $errors->first('agency.mail_sender_email') }}
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row mt-5">
                    <div class="col-3">
                        <input type="email" class="form-control" placeholder="Enter an email to receive the test" wire:model.defer="testEmail" />
                        <div class="validation-message text-danger">
                            {{ $errors->first('testEmail') }}
                        </div>
                    </div>
                    <div class="col-3">
                        <button class="btn btn-sm btn-secondary mr-2 px-2 py-2 text-uppercase" type="button" wire:click="test">Send Test Email</button>
                    </div>
                    <div class="col-6">
                        <div class="col d-flex align-items-end justify-content-end">
                            <div>
                                <button class="btn btn-sm btn-primary mr-2 px-2 py-2 text-uppercase" type="submit">
                                    Update Email Settings
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        @if($message)
                            <div class="text-success text-right px-4 mt-4">{{ $message }}</div>
                        @endif
                            @if($error)
                                <div class="text-danger text-right px-4 mt-4">{{ $error }}</div>
                            @endif
                    </div>
                </div>
            </form>
            <br/>
        </div>
    </section>
</div>

<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    New Connection
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        Make sure to contact your partner for credentials, and make sure you're added as an API user to in the system you're trying to connect to.<br />
                        By connection to another system, your users will be able to access the other system's data and make bookings under your account.<br/>
                        Make sure you have enough balance in your account.<br/>
                        <strong>Note: You can only connect to systems that are hosted on the same platform.</strong>
                    </div>
                </div>
                @if($errors->any())
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="col-md-3">
                    <label>Connection Name</label>
                    <input type="text" class="form-control" wire:model="connection.name" />
                </div>
                <div class="col-md-3">
                    <label>HOST</label>
                    <input type="text" class="form-control" wire:model="connection.host" />
                </div>
                <div class="col-md-3">
                    <label>Email</label>
                    <input type="text" class="form-control" wire:model="connection.email" />
                </div>
                <div class="col-md-3">
                    <label>Password</label>
                    <input type="text" class="form-control" wire:model="connection.password" />
                </div>
                <div class="col-12">
                    <div class="mt-2 text-danger font-size-14">Please note that your password will only be used once to connect to the other system. If for any reason you had to create another connection to the same system, you'll need to add your password again.</div>
                </div>
                <div class="col-12 text-right">
                    <button wire:click="connect" type="button" class="btn btn-primary font-weight-bold font-size-14 text-uppercase">Connect</button>
                </div>
            </div>
        </div>
    </section>
</div>

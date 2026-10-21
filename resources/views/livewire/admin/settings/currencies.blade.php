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
        @if (!empty($message))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        <div class="container-fluid mb-4">
            <form wire:submit="submit" class="pt-3">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">All conversion rates are in USD</div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="euro">EURO CONVERSION RATE</label>
                            <input type="number" class="form-control" step=".01" id="euro" wire:model="agency.euro" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <hr/>
            </form>
            <br/>
        </div>
    </section>
</div>

<div>
    <div class="admin-header position-relative">
        <div class="mx-auto w-100">&nbsp;</div>
    </div>
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    New Integration
                </h6>
            </div>
        </div>
        <div class="container-fluid">
            <form method="post" wire:submit.prevent="connect" class="row">
                <div class="col-md-6">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" wire:model="name" required />
                </div>
                <div class="col-md-6">
                    <label>Host URL</label>
                    <input type="url" class="form-control" name="url" wire:model="host" required />
                </div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" wire:model="email" required />
                </div>
                <div class="col-md-6">
                    <label>Password</label>
                    <input type="text" class="form-control" name="password" wire:model="password" required />
                </div>
                <div class="col-md-12 text-right">
                    <div>
                        <button type="submit" class="waves-effect mt-4 btn btn-primary">Connect</button>
                    </div>
                </div>
                <div class="col-12">
                    @if($response)
                        <div>{{ $response }}</div>
                    @endif
                </div>
            </form>
        </div>
    </section>
</div>
@push('scripts')
    <script>
        function checkUR(abc){
            let string = abc.value
            if(!(/^https:\/\//.test(string))){
                string = "https://";
            }
            abc.value=string
        }
    </script>
@endPush

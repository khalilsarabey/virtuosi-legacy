<div>
    <x-hotels-menu menu="rules" />
    <section class="mx-3 p-4 admin-container">
        <div class="border-bottom-blueGray-200 w-100">
            <div class="d-flex justify-content-between">
                <h6 class="header-card-title">
                    Hotel Rules
                    <div wire:loading.delay>
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </h6>
                <div>
                    <a class="btn btn-xs px-4 py-2 btn-outline-primary uppercase waves-effect" href="{{ route('admin.hotels.rules.create') }}">
                        Create New Rule
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-3">
                <div class="col-12">
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th style="width:10px"></th>
                            <th>Title</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody  wire:sortable="updateRuleOrder">
                            @foreach($hotelRules as $index => $rule)
                                <tr wire:sortable.item="{{ $rule->id }}" wire:key="rule-{{ $rule->id }}">
                                    <td wire:sortable.handle>
                                        <svg fill="#000000" height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                             viewBox="0 0 246.041 246.041" xml:space="preserve">
                                        <path d="M92.832,113.021h60.377v20H92.832V113.021z M123.021,0L73.208,59.188l37.129-0.001l-0.001,37.452h25.367l0.001-37.453
                                            l37.129-0.001L123.021,0z M123.021,246.041l49.812-59.189l-37.129,0.001l0.001-37.452h-25.367l-0.001,37.453l-37.13,0.001
                                            L123.021,246.041z"/>
                                        </svg>
                                    </td>
                                    <td>{{ $rule->title }}</td>
                                    <td>{{ $rule->status ? 'Active' : 'Inactive' }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.hotels.rules.edit', $rule->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <button class="btn btn-sm btn-danger" wire:click.prevent="deleteRule({{ $rule->id }})" onclick="return confirm('Are you sure you want to delete this rule?')">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">List Dealers</h4>
            </div>
        </div>
    </div>
    @include('modals.dealer-delete-modal')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dealers as $dealer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dealer->user->first_name }} {{ $dealer->user->last_name }}</td>
                                    <td>{{ $dealer->user->email }}</td>
                                    <td>{{ $dealer->user->country_code }} {{ $dealer->user->mobile }}</td>
                                    <td>{{ $dealer->cityDetails->name }}</td>
                                    <td wire:click="updateStatus({{ $dealer->id }})">
                                        @if ($dealer->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @elseif ($dealer->status == 0)
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary waves-effect"
                                            href="{{ route('dealer.edit', $dealer->id) }}">
                                            {{ __('Edit') }}
                                        </a>
                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="delete({{ $dealer->id }})">
                                            {{ __('Delete') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

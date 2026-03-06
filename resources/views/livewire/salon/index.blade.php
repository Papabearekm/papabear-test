<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">List Partners</h4>
            </div>
        </div>
    </div>

    @include('modals.partner-delete-modal')
    @include('modals.status-change-modal')

    <div class="row">
        <div class="col-lg-12 mt-3 mb-3 text-end">
            <button class="btn btn-success" wire:click="export()"><span class="fa fa-download"></span>&nbsp;&nbsp;Excel</button>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>Mobile Number</th>
                                <th>Whatsapp Number</th>
                                <th>Is Popular</th>
                                <th>In Homepage</th>
                                <th>Status</th>
                                <th>Upgrade</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salons as $salon)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $salon->salon->name }}</td>
                                    <td>{{ $salon->salon->city->name }}</td>
                                    <td>{{ $salon->country_code }} {{ $salon->mobile }}</td>
                                    <td>{{ $salon->salon->whatsapp_number }}</td>
                                    <td wire:click="updatePopular({{ $salon->salon->id }})">
                                        @if ($salon->salon->popular == 1)
                                            <span class="badge bg-success">Yes</span>
                                        @elseif ($salon->salon->popular == 0)
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td wire:click="updateInHome({{ $salon->salon->id }})">
                                        @if ($salon->salon->in_home == 1)
                                            <span class="badge bg-success">Yes</span>
                                        @elseif ($salon->salon->in_home == 0)
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td wire:click="updateStatus({{ $salon->salon->id }})">
                                        @if ($salon->salon->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @elseif ($salon->salon->status == 0)
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $salon->salon->upgrade == 1 ? 'Yes' : 'No' }}
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary waves-effect"
                                            href="{{ route('salon.edit', $salon->id) }}">
                                            {{ __('Edit') }}
                                        </a>
                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="delete({{ $salon->id }})" wire:key="{{ $salon->id }}">
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

<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">List Freelancers</h4>
            </div>
        </div>
    </div>

    @include('modals.freelancer-delete-modal')

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
                            @foreach ($freelancers as $freelancer)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $freelancer->first_name }} {{ $freelancer->last_name }}</td>
                                    <td>{{ $freelancer->individual->city->name }}</td>
                                    <td>{{ $freelancer->country_code }} {{ $freelancer->mobile }}</td>
                                    <td>{{ $freelancer->individual->whatsapp_number }}</td>
                                    <td wire:click="updatePopular({{ $freelancer->individual->id }})">
                                        @if ($freelancer->individual->popular == 1)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td wire:click="updateInHome({{ $freelancer->individual->id }})">
                                        @if ($freelancer->individual->in_home == 1)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td wire:click="updateStatus({{ $freelancer->individual->id }})">
                                        @if ($freelancer->individual->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $freelancer->individual->upgrade == 1 ? 'Yes' : 'No' }}
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary waves-effect"
                                            href="{{ route('freelancer.edit', $freelancer->id) }}">
                                            {{ __('Edit') }}
                                        </a>

                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="delete({{ $freelancer->id }})">
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

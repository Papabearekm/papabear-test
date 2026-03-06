<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">List Complaints</h4>
            </div>
        </div>
    </div>

    @include('modals.complaint-delete-modal')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>User</th>
                                <th>Issue With</th>
                                <th>Partner</th>
                                <th>Title</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($complaints as $complaint)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{  $complaint->user ? ($complaint->user->first_name . ' ' . $complaint->user->last_name) : '-'}}</td>
                                    <td>{{ $complaint->issue }} ({{$complaint->complaints_on == 1 ? 'Product Order' : 'Appointment'}})</td>
                                    <td>{{ (\App\Models\User::find( $complaint->freelancer_id)->first_name . ' ' . \App\Models\User::find( $complaint->freelancer_id)->last_name) ?? '-' }}</td>
                                    <td>{{ $complaint->title }}</td>
                                    <td>{{ $complaint->reason }}</td>
                                    <td>{{ $complaint->statusMessage }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="delete({{ $complaint->id }})">
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

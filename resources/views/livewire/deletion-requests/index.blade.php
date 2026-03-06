<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Deletion Requests</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>User</th>
                                <th>Deletion Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $request->user ?  ($request->user->first_name . ' ' . $request->user->last_name) : '-' }}</td>
                                    <td>{{ $request->deletion_time }}</td>
                                    <td>
                                        @if ($request->status == "Completed")
                                            <span class="badge bg-success">Deleted</span>
                                        @else
                                            <span class="badge bg-danger">{{ $request->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($request->status == "Pending")
                                            <a class="btn btn-sm btn-primary waves-effect" href="#!" wire:click="completeDeletion({{ $request->id }})">
                                                {{ __('Delete') }}
                                            </a>
                                        @else
                                            --
                                        @endif
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

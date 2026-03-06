<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">List Banners</h4>
            </div>
        </div>
    </div>

    @include('modals.banner-delete-modal')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Cover</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banners as $banner)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a target="_blank" href="{{ $banner->cover ? Storage::disk('spaces')->url($banner->cover) : 'javascript:void(0)' }}">
                                            <img src="{{ $banner->cover ? Storage::disk('spaces')->url($banner->cover) : asset('assets/images/dummy.jpeg') }}" alt="banner"
                                                width="50" height="50">
                                        </a>
                                    </td>
                                    <td>{{ $banner->user->first_name . ' ' . @$banner->user->last_name }}</td>
                                    <td wire:click="updateStatus({{ $banner->id }})">
                                        @if ($banner->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary waves-effect"
                                            href="{{ route('partner-ads.edit', $banner->id) }}">
                                            {{ __('View / Edit') }}
                                        </a>

                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="delete({{ $banner->id }})">
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

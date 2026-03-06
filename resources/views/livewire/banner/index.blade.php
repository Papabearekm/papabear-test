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
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-2">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                            </div>
                            <div class="col-md-2">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                            </div>
                            <div class="col-md-2">
                                <br>
                                <button class="btn btn-primary mt-2">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Cover</th>
                                <th>User</th>
                                <th>City / Coords</th>
                                <th>Ads Plan</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banners as $banner)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a target="_blank" href="{{ $banner->cover ? Storage::disk('spaces')->url($banner->cover) : '' }}">
                                            <img src="{{ $banner->cover ? Storage::disk('spaces')->url($banner->cover) : '' }}" alt="banner"
                                                width="50" height="50">
                                        </a>
                                    </td>
                                    <td>{{ $banner->user->first_name . ' ' . @$banner->user->last_name }}</td>
                                    <td>{{ $banner->city_id ? $banner->city->name : $banner->lat. ',' . $banner->lng }}</td>
                                    <td>
                                        {{ $banner->position }} <br>
                                        {{ $banner->price }}
                                    </td>
                                    <td>{{ Carbon\carbon::parse($banner->from)->format('d-m-Y') }}</td>
                                    <td>{{ Carbon\carbon::parse($banner->to)->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($banner->status == "Active")
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary waves-effect"
                                            href="{{ route('banner.edit', $banner->id) }}">
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

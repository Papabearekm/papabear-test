<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Freelancers Join Request List</h4>
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
                                <th>Name</th>
                                <th>City</th>
                                <th>Categories</th>
                                <th>Fees Start</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                @php
                                    $categories = json_decode($request->categories, true); // Decode the categories from the request
                                @endphp
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $request->first_name . ' ' . @$request->last_name }}</td>
                                    <td>{{ $request->city->name }}</td>
                                    <td>
                                        @php
                                            $categoryNames = App\Models\Category::whereIn('id', $categories)->pluck('name')->toArray();
                                        @endphp
                                        {{ implode(', ', $categoryNames) }}<br>
                                    </td>
                                    <td>{{ number_format($request->fee_start, 2) }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-success waves-effect"
                                            wire:click.prevent="approve({{ $request->id }})">
                                            {{ __('Approve') }}
                                        </a>
                                        
                                        <a class="btn btn-sm btn-primary waves-effect"
                                            href="{{ route('freelancer.request.view', $request->id) }}">
                                            {{ __('View') }}
                                        </a>
                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="destroy({{ $request->id }})" wire:key="{{ $request->id }}">
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

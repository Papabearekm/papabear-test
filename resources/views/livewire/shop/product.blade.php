<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">List Products</h4>
            </div>
        </div>
    </div>

    @include('modals.product-delete-modal')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Cover</th>
                                <th>Name</th>
                                <th>Partner/Freelancer</th>
                                <th>Top Product</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a target="_blank" href="{{ Storage::disk('spaces')->url($product->cover) }}">
                                            <img src="{{ Storage::disk('spaces')->url($product->cover) }}" alt="image"
                                                width="50" height="50">
                                        </a>
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    @php
                                    $salon = \App\Models\Salon::where('uid', $product->freelacer_id)->first();
                                    @endphp
                                    <td>{{ $salon ? $salon->name : ($product->freelancer->first_name . ' ' . @$product->freelancer->last_name) }}
                                    </td>
                                    <td wire:click="updateInHome({{ $product->id }})">
                                        @if ($product->in_home == 1)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td wire:click="updateStatus({{ $product->id }})">
                                        @if ($product->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal" wire:click="delete({{ $product->id }})">
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

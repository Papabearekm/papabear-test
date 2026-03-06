<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">List Partner Product Orders</h4>
            </div>
        </div>
    </div>

    @include('modals.orders-delete-modal')

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
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $loop->index }}" {{ request()->query('status') ? (request()->query('status') == $loop->index ? 'selected' : '') : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
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
                                <th>Partner</th>
                                <th>User</th>
                                <th>Products</th>
                                <th>Grand Total</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @php
                                    $item_arrays = json_decode($order->orders, true);
                                @endphp
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    @php
                                    $partner = \App\Models\Salon::where('uid',$order->salon_id)->first();
                                    @endphp
                                    <td>{{ $partner ? $partner->name : '-' }}</td>
                                    <td>{{ @$order->user->first_name . ' ' . @$order->user->last_name }}
                                    </td>
                                    <td>
                                        @foreach ($item_arrays as $item)
                                            - {{ $item['name'] }}<br>
                                        @endforeach
                                    </td>
                                    <td>{{ number_format($order->grand_total, 2) }}</td>
                                    <td>{{ $order->payment_method->name }}</td>
                                    <td>
                                        <span class="badge {{ in_array($order->status, [2,5,6,7,8]) ? 'bg-danger' : 'bg-success' }}">{{ $statuses[$order->status] }}</span>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary waves-effect"
                                            href="{{ route('salon.order.view', $order->id) }}">
                                            {{ __('View') }}
                                        </a>
                                        @if($order->status == 4)
                                        &nbsp;
                                        <a class="btn btn-sm btn-success waves-effect"
                                            target="_blank"
                                            href="/api/v1/product_order/printCommissionInvoice?id={{ $order->id }}&token={{ csrf_token() }}">
                                            {{ __('Commission Invoice') }}
                                        </a>
                                        @endif
                                        {{-- <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="delete({{ $appointment->id }})">
                                            {{ __('Delete') }}
                                        </button> --}}
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

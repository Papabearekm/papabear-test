<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">List Orders</h4>
            </div>
        </div>
    </div>

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
                                <th>Product</th>
                                <th>Partner</th>
                                <th>User</th>
                                <th>Grand Total</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @php
                                $details = json_decode($order->orders, true);
                                @endphp
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>@foreach($details as $detail)
                                        {{ $detail['name'] }} @if($loop->last) @else , @endif
                                        @endforeach
                                    </td>
                                    @php
                                    $freelancer = \App\Models\User::find($order->freelancer_id == 0 ? $order->salon_id : $order->freelancer_id);
                                    $user = \App\Models\User::find($order->uid);
                                    $salon = $order->salon_id != 0 ? \App\Models\Salon::where('uid', $order->salon_id)->first() : null;
                                    @endphp
                                    <td>{{ $salon ? $salon->name : $freelancer->first_name . ' ' . $freelancer->last_name}}</td>
                                    <td>{{ $user->first_name . ' ' . $user->last_name}}</td>
                                    <td>{{ $order->grand_total }}</td>
                                    <td>{{ $order->pay_key == 'COD' ? 'COD' : 'Online Payment' }}</td>
                                    <td>
                                        <span class="badge {{ in_array($order->status, [2,5,6,7,8]) ? 'bg-danger' : 'bg-success' }}">{{ $statuses[$order->status] }}</span>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary waves-effect"
                                            href="{{ route('shop.order-details', $order->id) }}">
                                            {{ __('View') }}
                                        </a>
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

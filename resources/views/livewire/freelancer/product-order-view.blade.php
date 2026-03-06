<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-danger">Product Order Details of {{ $order->user->first_name . ' ' . @$order->user->last_name }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th>User :</th>
                                <td>{{ $order->user->first_name . ' ' . @$order->user->last_name }}</td>
                            </tr>
                            <tr>
                                <th>Partner :</th>
                                @php
                                    $partner = \App\Models\User::where('id',$order->freelancer_id)->first();
                                    @endphp
                                <td>{{ $partner ? ($partner->first_name . ' ' . $partner->last_name) : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Products :</th>
                                <td>
                                    @php
                                        $category_arrays = json_decode($order->orders, true);
                                        $discount = 0;
                                    @endphp
                                    @foreach ($category_arrays as $service)
                                        - {{ $service['name'] }} [Qty: {{ $service['quantity'] }}]
                                        <br>
                                        @php
                                            $discount += $service['discount'] ?? 0;
                                        @endphp
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Status :</th>
                                <td>{{ $order->paid_method ? 'Paid' : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Payment Method :</th>
                                <td>{{ $order->paid_method == 1 ? 'COD' : 'Online Payment' }}</td>
                            </tr>
                            <tr>
                                <th>Date :</th>
                                <td>{{ Carbon\carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Discount :</th>
                                <td>{{ $order->discount ?? $discount }}</td>
                            </tr>
                            <tr>
                                <th>Service Tax (18%):</th>
                                <td>{{ $order->tax ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Delivery Charge :</th>
                                <td>{{ $order->delivery_charge ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total :</th>
                                <td>{{ $order->total ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Grand Total :</th>
                                <td>{{ $order->grand_total ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Completed on :</th>
                                <td>{{ $order->status == 4 ? Carbon\Carbon::parse($order->updated_at)->format('d-m-Y') : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end table-->
                </div>
            </div>
        </div>
    </div>
</div>

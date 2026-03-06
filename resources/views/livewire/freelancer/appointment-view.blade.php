<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-danger">Appointment Details of {{ $appointment->user->first_name . ' ' . @$appointment->user->last_name }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th>User :</th>
                                <td>{{ $appointment->user->first_name . ' ' . @$appointment->user->last_name }}</td>
                            </tr>
                            <tr>
                                <th>Freelancer :</th>
                                @php
                                    $partner = \App\Models\User::find($appointment->freelancer_id);
                                @endphp
                                <td>{{ $partner ? $partner->first_name . ' ' . $partner->last_name : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Services :</th>
                                <td>
                                    @php
                                        $category_arrays = json_decode($appointment->items, true);
                                    @endphp
                                    @foreach ($category_arrays['services'] as $service)
                                        - {{ $service ? $service['name'] : '' }} [{{ $service['gender'] == 0 ? 'Kids' : ($service['gender'] == 1 ? 'Male' : ($service['gender'] == 2 ? 'Female' : 'Family')) }}]
                                        <br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Packages :</th>
                                <td>
                                    @foreach ($category_arrays['packages'] as $package)
                                        - {{ $package ? $package['name'] : '' }}
                                        <br>
                                        Services Included:
                                        <br>
                                        @foreach ($package['services'] as $p_service)
                                            &nbsp;&nbsp;&nbsp;* {{ $p_service['name'] }}
                                            <br>
                                        @endforeach
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Status :</th>
                                <td>{{ $appointment->pay_method ? 'Paid' : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Payment Method :</th>
                                <td>{{ $appointment->pay_method == 1 ? 'COD' :  'Online Payment' }}</td>
                            </tr>
                            <tr>
                                <th>Appointment Date :</th>
                                <td>{{ Carbon\carbon::parse($appointment->save_date)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Created Date :</th>
                                <td>{{ Carbon\carbon::parse($appointment->created_at)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Slot :</th>
                                <td>{{ $appointment->slot ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Service at :</th>
                                <td>{{ $appointment->appointments_to == 0  ? 'At Business' : 'At Home' }}</td>
                            </tr>
                            <tr>
                                <th>Distance Cost :</th>
                                <td>{{ $appointment->distance_cost ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Discount :</th>
                                <td>{{ $appointment->discount ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Service Tax (18%):</th>
                                <td>{{ $appointment->serviceTax ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total :</th>
                                <td>{{ $appointment->total ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Grand Total :</th>
                                <td>{{ $appointment->grand_total ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end table-->
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Partner Wise Appointment Report</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mt-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-5">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                            </div>
                            <div class="col-md-5">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                            </div>
                            <div class="col-md-2 text-center">
                                <br>
                                <button class="btn btn-primary mt-2">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-3 mb-3 text-end">
            <button class="btn btn-success" wire:click="export()"><span class="fa fa-download"></span>&nbsp;&nbsp;Excel</button>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Partner</th>
                                <th>Is Premium</th>
                                <th>No.Of Appointments</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salons as $salon)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $salon->name }}</td>
                                    <td>{{ $salon->upgrade == 0 ? 'No' : 'Yes' }}</td>
                                    <td>
                                        {{ App\Models\Appointments::join('salon', 'salon.uid', '=', 'appointments.salon_id')
                                            ->join('users', 'users.id', '=', 'salon.uid')
                                            ->whereBetween('appointments.save_date', [$start_date, $end_date])
                                            ->where('users.type', 'salon')->where('appointments.salon_id', $salon->uid)
                                            ->count() }}
                                    </td>
                                    <td>
                                        {{ App\Models\Appointments::join('salon', 'salon.uid', '=', 'appointments.salon_id')
                                            ->join('users', 'users.id', '=', 'salon.uid')
                                            ->whereBetween('appointments.save_date', [$start_date, $end_date])
                                            ->where('users.type', 'salon')->where('appointments.salon_id', $salon->uid)
                                            ->sum('grand_total') }}
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

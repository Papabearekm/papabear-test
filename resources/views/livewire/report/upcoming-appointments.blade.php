<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Upcoming Appointments Report</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mt-3 mb-3 text-end">
            <button class="btn btn-success" wire:click="export()"><span class="fa fa-download"></span>&nbsp;&nbsp;Excel</button>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Customer</th>
                                <th>Partner</th>
                                <th>Appointment Date</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item['customer'] }}</td>
                                    <td>{{ $item['partner'] }}</td>
                                    <td>{{ $item['date'] }}</td>
                                    <td>{{ $item['status'] }}</td>
                                    <td>{{ $item['payment_status'] }}</td>
                                    <td>{{ $item['amount'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

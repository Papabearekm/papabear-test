<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Earnings Report</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mt-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                            </div>
                            <div class="col-md-4">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                            </div>
                            <div class="col-md-4">
                                <br>
                                <button class="btn btn-primary mt-2">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-3 mb-3 text-end">
            <button class="btn btn-success" wire:click="export()"><span class="fa fa-download"></span>&nbsp;&nbsp;Excel</button>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Product Sales</th>
                                <th>Product Commission</th>
                                <th>Appointments</th>
                                <th>Appointments Commission</th>
                                <th>Ads</th>
                                <th>Upgrades</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item['date'] }}</td>
                                    <td>{{ $item['product_income'] }}</td>
                                    <td>{{ $item['product_income_commission'] }}</td>
                                    <td>{{ $item['appointments_income'] }}</td>
                                    <td>{{ $item['appointments_income_commission'] }}</td>
                                    <td>{{ $item['ads_income'] }}</td>
                                    <td>{{ $item['upgrades_income'] }}</td>
                                    <td>{{ $item['total_income'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

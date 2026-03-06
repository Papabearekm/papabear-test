<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Wallet Report</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mt-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                            </div>
                            <div class="col-md-3">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                            </div>
                            <div class="col-md-3">
                                <label>Type</label>
                                <select class="form-control" name="type">
                                    <option value="">All</option>
                                    <option value="Freelancer" {{ request()->query('type') == 'Freelancer' ? 'selected' : '' }}>Freelancer</option>
                                    <option value="Partner" {{ request()->query('type') == 'Partner' ? 'selected' : '' }}>Partner</option>
                                </select>
                            </div>
                            <div class="col-md-3">
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
                                <th>Name</th>
                                <th>Type</th>
                                <th>Wallet Balance</th>
                                <th>Total Withdrawals</th>
                                <th>Withdrawal Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['type'] }}</td>
                                    <td>{{ $item['balance'] }}</td>
                                    <td>{{ $item['withdrawal_count'] }}</td>
                                    <td>{{ $item['withdrawal_amount'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

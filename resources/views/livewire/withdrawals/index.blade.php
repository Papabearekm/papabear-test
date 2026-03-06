<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Withdrawals</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
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
                                <label>City</label>
                                <select name="city" class="form-control">
                                    <option value="">Select City</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" {{ $city->id == request()->query('city') ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Withdrawal Date</th>
                                <th>Current Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $withdrawal->user }}</td>
                                    <td>{{ $withdrawal->amount }}</td>
                                    <td>{{ carbon\Carbon::parse($withdrawal->withdrawal_date)->format('d-m-Y') }}</td>
                                    <td>{{ $withdrawal->user_balance }}</td>
                                    <td>
                                        @if ($withdrawal->status == "Completed")
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">{{ $withdrawal->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($withdrawal->status == "Processing")
                                            <a class="btn btn-sm btn-primary waves-effect" href="#!" wire:click="completePayment({{ $withdrawal->id }})">
                                                {{ __('Complete') }}
                                            </a>
                                        @else
                                            Completed on {{ carbon\Carbon::parse($withdrawal->updated_at)->setTimezone('Asia/Kolkata')->format('d-m-Y') }}
                                        @endif
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

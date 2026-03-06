<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">List Coupons</h4>
            </div>
        </div>
    </div>

    @include('modals.offer-delete-modal')

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
                                <th>Coupon</th>
                                <th>Discount</th>
                                <th>Expire</th>
                                <th>Min Cart Value</th>
                                <th>Maximum Usage</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offers as $offer)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $offer->code }}</td>
                                    <td>{{ $offer->discount }}</td>
                                    <td>{{ $offer->expire }}</td>
                                    <td>{{ $offer->min_cart_value }}</td>
                                    <td>{{ $offer->max_usage }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-primary waves-effect"
                                            href="{{ route('offer.edit', $offer->id) }}">
                                            {{ __('View / Edit') }}
                                        </a>

                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="delete({{ $offer->id }})">
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

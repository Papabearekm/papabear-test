<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Category Wise Partner/Freelancer Register Report</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mt-3 mb-3"></div>
        <div class="col-lg-6 mt-3 mb-3 text-end">
            <button class="btn btn-success" wire:click="export()"><span class="fa fa-download"></span>&nbsp;&nbsp;Excel</button>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Category</th>
                                <th>No.Of Partner</th>
                                <th>No.Of Freelancer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        {{ App\Models\Salon::join('users', 'users.id', '=', 'salon.uid')
                                            ->where('users.type', 'salon')->whereIn('users.id', $salons)
                                            ->whereJsonContains('salon.categories', (int) $category->id)
                                            ->count() }}
                                    </td>
                                    <td>
                                        {{ App\Models\Individual::join('users', 'users.id', '=', 'individual.uid')
                                            ->whereIn('users.type', ['freelancer', 'individual'])->whereIn('users.id', $freelancers)
                                            ->whereJsonContains('individual.categories', (int) $category->id)
                                            ->count() }}
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

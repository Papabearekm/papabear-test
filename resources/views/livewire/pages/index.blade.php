<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">App Pages</h4>
            </div>
        </div>
    </div>

    @include('modals.page-edit-modal')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $page)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $page->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary waves-effect" data-bs-toggle="modal"
                                            data-bs-target="#edit_modal" wire:click="edit({{ $page->id }})">
                                            {{ __('Edit') }}
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

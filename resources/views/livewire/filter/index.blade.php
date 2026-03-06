<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Filters</h4>
            </div>
        </div>
    </div>

    @include('modals.filter-delete-modal')

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="filter_id" wire:model="filter_id">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Filter Name') }}</label>
                                    <input type="text" name="name" wire:model.defer="name"
                                        value="{{ old('name') }}" class="form-control" id="formrow-email-input">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
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
                            @foreach ($filters as $filter)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $filter->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary waves-effect"
                                            wire:click="edit({{ $filter->id }})">
                                            {{ __('View / Edit') }}
                                        </button>

                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="delete({{ $filter->id }})">
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

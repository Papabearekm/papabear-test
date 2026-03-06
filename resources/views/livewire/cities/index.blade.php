<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Cities</h4>
            </div>
        </div>
    </div>

    @include('modals.cities-delete-modal')

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('City Name') }}</label>
                                    <input type="text" name="name" wire:model.defer="name"
                                        value="{{ old('name') }}" class="form-control" id="formrow-email-input">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Country') }}</label>
                                    <select name="country" wire:model.defer="country"
                                        class="form-control">
                                        <option>-- select --</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country }}">{{ $country }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="text-muted">
                                        Select Latitude & Longitude From here: <a class="text-info"
                                            href="https://www.mapcoordinates.net/en"
                                            target="_blank">https://www.mapcoordinates.net/en</a>
                                        <br><br>
                                        **Please enter valid Latitude & Longitude otherwise app may not work properly.**
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Latitude') }}</label>
                                    <input type="text" name="latitude" wire:model.defer="latitude"
                                        value="{{ old('latitude') }}" class="form-control" id="formrow-email-input">
                                    @error('latitude')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Longitude') }}</label>
                                    <input type="text" name="longitude" wire:model.defer="longitude"
                                        value="{{ old('longitude') }}" class="form-control" id="formrow-email-input">
                                    @error('longitude')
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
                                <th>City Name</th>
                                <th>Country</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cities as $city)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $city->name }}</td>
                                    <td>{{ $city->country }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary waves-effect"
                                            wire:click="edit({{ $city->id }})">
                                            {{ __('View / Edit') }}
                                        </button>

                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal" wire:click="delete({{ $city->id }})">
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

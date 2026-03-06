<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Add Banner</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <center>
                                        @if ($image)
                                            <img src="{{ $image->temporaryUrl() }}" alt="preview" width="250"
                                                height="120" class="p-2">
                                        @else
                                            <img src="{{ asset('assets/images/dummy.jpeg') }}" alt="preview"
                                                width="250" height="120" class="p-2">
                                        @endif
                                    </center>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Cover Photo') }}</label>
                                    <input type="file" name="image" wire:model="image" value="{{ old('image') }}"
                                        class="form-control">
                                    @error('image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Type') }}</label>
                                    <select name="selection" wire:model.live="selection" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="1">Freelancers</option>
                                        <option value="2">Partners</option>
                                    </select>
                                    @error('selection')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            @if ($selection == 1)
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="formrow-email-input" class="form-label">{{ __('Value') }}</label>
                                        <select name="selection_value" wire:model.live="selection_value" class="form-control">
                                            <option value="">Select Any</option>
                                            @foreach ($individuals as $individual)
                                                <option value="{{ $individual->id }}">{{ $individual->first_name }} {{ $individual->last_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selection_value')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @elseif ($selection == 2)
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="formrow-email-input" class="form-label">{{ __('Value') }}</label>
                                        <select name="selection_value" wire:model.live="selection_value" class="form-control">
                                            <option value="">Select Any</option>
                                            @foreach ($partners as $partner)
                                                <option value="{{ $partner->id }}">{{ $partner->salon->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('selection_value')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @elseif ($selection == 4)
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="formrow-email-input" class="form-label">{{ __('Value') }}</label>
                                        <select name="selection_value" wire:model.live="selection_value" class="form-control">
                                            <option value="">Select Any</option>
                                            @foreach ($product_categories as $product_category)
                                                <option value="{{ $product_category->id }}">
                                                    {{ $product_category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('selection_value')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @elseif ($selection == 5)
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="formrow-email-input" class="form-label">{{ __('Value') }}</label>
                                        <select name="selection_value" wire:model.live="selection_value" class="form-control">
                                            <option value="">Select Any</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('selection_value')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @elseif ($selection == 6)
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="formrow-email-input" class="form-label">{{ __('Value') }}</label>
                                        <input type="text" name="selection_value" wire:model.live="selection_value"
                                            value="{{ old('selection_value') }}" class="form-control">
                                        @error('selection_value')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Position') }}</label>
                                    <select name="position" wire:model="position" class="form-control">
                                        <option value="0">Home</option>
                                        <option value="1">Search</option>
                                    </select>
                                    @error('position')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Title') }}</label>
                                    <input type="text" name="title" wire:model="title"
                                        value="{{ old('title') }}" class="form-control">
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Link') }}</label>
                                    <input type="text" name="link" wire:model="link"
                                        value="{{ old('link') }}" class="form-control">
                                    @error('link')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Price') }}</label>
                                    <input type="number" name="price" wire:model="price"
                                        value="{{ old('price') }}" class="form-control" placeholder="0.00">
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Number of days') }}</label>
                                    <input type="number" name="end_date" wire:model="end_date"
                                        value="{{ old('end_date') }}" class="form-control">
                                    @error('end_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md mt-3">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
                                                height="250" class="p-2">
                                        @else
                                            <img src="{{ asset('assets/images/dummy.jpeg') }}" alt="preview"
                                                width="250" height="250" class="p-2">
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
                        </div>

                        <div class="row">
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

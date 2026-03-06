<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Add Service</h4>
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
                            {{-- <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Select User Type') }}</label>
                                    <select name="type" wire:model.live="type" class="form-control">
                                        <option value="">-- select --</option>
                                        <option value="1">Partner</option>
                                        <option value="2">Freelancer</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            @if ($users)
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="formrow-email-input"
                                            class="form-label">{{ __('Select Freelancer / Partner') }}</label>
                                        <select name="partner" wire:model.live="partner" class="form-control">
                                            <option>-- select --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->salon->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('partner')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif --}}

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Select Category') }}</label>
                                    <select name="category" wire:model.live="category" class="form-control">
                                        <option>-- select --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Service Name') }}</label>
                                    <input type="text" name="name" wire:model="name" value="{{ old('name') }}"
                                        class="form-control" id="formrow-email-input">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('HSN / SAC Code') }}</label>
                                    <input type="text" name="hsn_code" wire:model="hsn_code" value="{{ old('hsn_code') }}"
                                        class="form-control" id="formrow-email-input">
                                    @error('hsn_code')
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

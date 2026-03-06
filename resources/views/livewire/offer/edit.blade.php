<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Coupon</h4>
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" wire:model="name"
                                        value="{{ old('name', $name) }}" class="form-control" id="formrow-email-input">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Short Descriptions') }}</label>
                                    <input type="text" name="short_descriptions" wire:model="short_descriptions"
                                        value="{{ old('short_descriptions', $short_descriptions) }}" class="form-control" id="formrow-email-input">
                                    @error('short_descriptions')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Coupon Code') }}</label>
                                    <input type="text" name="code" wire:model="code"
                                        value="{{ old('code', $code) }}" class="form-control" id="formrow-email-input">
                                    @error('code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Discount') }}</label>
                                    <input type="number" name="discount" wire:model="discount"
                                        value="{{ old('discount', $discount) }}" class="form-control" id="formrow-email-input">
                                    @error('discount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Max Discount') }}</label>
                                    <input type="number" name="upto" wire:model="upto" value="{{ old('upto') }}"
                                        class="form-control" id="formrow-email-input">
                                    @error('upto')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Type') }}</label>
                                    <select name="type" wire:model="type" class="form-control">
                                        <option value="1">%</option>
                                        <option value="2">Flat</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Max Usage') }}</label>
                                    <input type="number" name="max_usage" wire:model="max_usage"
                                        value="{{ old('max_usage') }}" class="form-control" id="formrow-email-input">
                                    @error('max_usage')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Min Cart Value') }}</label>
                                    <input type="number" name="min_cart_value" wire:model="min_cart"
                                        value="{{ old('min_cart_value') }}" class="form-control" id="formrow-email-input">
                                    @error('min_cart')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Expiry Date') }}</label>
                                    <input type="date" name="expire" wire:model="expiry_date"
                                        value="{{ old('expiry_date') }}" class="form-control" id="formrow-email-input">
                                    @error('expiry_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Tick Partners or Freelancers') }}</label> <br>
                                    @foreach ($users as $user)
                                        <input type="checkbox" class="form-check-input"
                                            id="formrow-email-input" value="{{ $user->id }}" {{ in_array($user->id, $selectedFreelancers) ? 'checked' : ''}}> {{ $user->salon ? $user->salon->name : $user->first_name . ' ' . $user->last_name }}
                                    @endforeach
                                    @error('freelancers')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md mt-3">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

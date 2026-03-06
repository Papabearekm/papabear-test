<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Add Dealer</h4>
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
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <center>
                                        @if ($image)
                                            <img src="{{ $image->temporaryUrl() }}" alt="preview" width="150"
                                                height="150" class="p-2">
                                        @else
                                            <img src="{{ asset('assets/images/dummy.jpeg') }}" alt="preview"
                                                width="150" height="150" class="p-2">
                                        @endif
                                    </center>
                                </div>
                            </div>

                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <center>
                                        <label for="formrow-email-input"
                                            class="form-label">{{ __('Cover Photo') }}</label>
                                        <input type="file" name="image" wire:model="image"
                                            value="{{ old('image') }}" class="form-control">
                                        @error('image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </center>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <h4 class="p-3 text-info">Personal Details</h4>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('First Name') }}</label>
                                    <input type="text" name="first_name" wire:model="first_name"
                                        value="{{ old('first_name') }}" class="form-control" id="formrow-email-input">
                                    @error('first_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name" wire:model="last_name"
                                        value="{{ old('last_name') }}" class="form-control" id="formrow-email-input">
                                    @error('last_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Email') }}</label>
                                    <input type="email" name="email" wire:model="email" value="{{ old('email') }}"
                                        class="form-control" id="formrow-email-input">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Password') }}</label>
                                    <input type="password" name="password" wire:model="password"
                                        value="{{ old('password') }}" class="form-control" id="formrow-email-input">
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Country Code') }}</label>
                                    <input type="text" name="country_code" wire:model="country_code"
                                        value="{{ old('country_code') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('country_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Mobile') }}</label>
                                    <input type="number" name="mobile" wire:model="mobile"
                                        value="{{ old('mobile') }}" class="form-control" id="formrow-email-input">
                                    @error('mobile')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Gender') }}</label>
                                    <select name="gender" wire:model="gender" class="form-control">
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                        <option value="3">Other</option>
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <h4 class="p-3 text-info">Other Details</h4>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Address') }}</label>
                                    <textarea name="address" wire:model="address" class="form-control" id="formrow-email-input"></textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('City') }}</label>
                                    <select name="city" wire:model="city" class="form-control">
                                        <option>-- select --</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Pin Code') }}</label>
                                    <input type="text" name="zip_code" wire:model="zip_code"
                                        value="{{ old('zip_code') }}" class="form-control" id="formrow-email-input">
                                    @error('zip_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Id Proof Front') }}</label>
                                    <input type="file" name="id_proof" wire:model="id_proof"
                                        value="{{ old('id_proof') }}" class="form-control" id="formrow-email-input">
                                    @error('id_proof')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Id Proof Back') }}</label>
                                    <input type="file" name="id_proof_back" wire:model="id_proof_back"
                                        value="{{ old('id_proof_back') }}" class="form-control" id="formrow-email-input">
                                    @error('id_proof_back')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <h4 class="p-3 text-info">Bank Details</h4>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Bank Name') }}</label>
                                    <input type="text" name="bank_name" wire:model="bank_name"
                                        value="{{ old('bank_name') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('bank_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Bank IFSC') }}</label>
                                    <input type="text" name="bank_ifsc" wire:model="bank_ifsc"
                                        value="{{ old('bank_ifsc') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('bank_ifsc')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Bank Account Number') }}</label>
                                    <input type="number" name="bank_account_number" wire:model="bank_account_number"
                                        value="{{ old('bank_account_number') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('bank_account_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Bank Customer Name') }}</label>
                                    <input type="text" name="bank_customer_name" wire:model="bank_customer_name"
                                        value="{{ old('bank_customer_name') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('bank_customer_name')
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

<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">App Settings</h4>
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
                            <h4 class="p-3 text-info">Basic Information</h4>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('App Name') }}</label>
                                    <input type="text" name="name" wire:model="name" class="form-control"
                                        id="formrow-email-input">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Contact Number') }}</label>
                                    <input type="number" name="number" wire:model="number" value="{{ old('number') }}"
                                        class="form-control" id="formrow-email-input">
                                    @error('number')
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
                                    <label for="formrow-email-input" class="form-label">{{ __('Address') }}</label>
                                    <input type="text" name="address" wire:model="address"
                                        value="{{ old('address') }}" class="form-control" id="formrow-email-input">
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('City') }}</label>
                                    <input type="text" name="city" wire:model="city" value="{{ old('city') }}"
                                        class="form-control" id="formrow-email-input">
                                    @error('city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('State') }}</label>
                                    <input type="text" name="state" wire:model="state" value="{{ old('state') }}"
                                        class="form-control" id="formrow-email-input">
                                    @error('state')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Zip') }}</label>
                                    <input type="text" name="zip" wire:model="zip" value="{{ old('zip') }}"
                                        class="form-control" id="formrow-email-input">
                                    @error('zip')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Country Name') }}</label>
                                    <input type="text" name="country" wire:model="country"
                                        value="{{ old('country') }}" class="form-control" id="formrow-email-input">
                                    @error('country')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Allow Distance') }}</label>
                                    <input type="text" name="allow_distance" wire:model="allow_distance"
                                        value="{{ old('allow_distance') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('allow_distance')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Default City') }}</label>
                                    <input type="text" name="default_city" wire:model="default_city"
                                        value="{{ old('default_city') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('default_city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Default Zip') }}</label>
                                    <input type="text" name="default_zip" wire:model="default_zip"
                                        value="{{ old('default_zip') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('default_zip')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Delivery Charge') }}</label>
                                    <input type="number" name="delivery_charge" wire:model="delivery_charge"
                                        value="{{ old('delivery_charge') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('delivery_charge')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Tax Charge') }}</label>
                                    <input type="text" name="tax_charge" wire:model="tax_charge"
                                        value="{{ old('tax_charge') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('tax_charge')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Commission Percentage') }}</label>
                                    <input type="number" name="commission_percentage" wire:model="commission_percentage"
                                        value="{{ old('commission_percentage') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('commission_percentage')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Delivery Type') }}</label>
                                    <select name="delivery_type" wire:model="delivery_type" class="form-control">
                                        <option>-- select --</option>
                                        <option value="0">Fixed</option>
                                        <option value="1">KM</option>
                                    </select>
                                    @error('delivery_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Have Shop') }}</label>
                                    <select name="have_shop" wire:model="have_shop" class="form-control">
                                        <option>-- select --</option>
                                        <option value="1">YES</option>
                                        <option value="0">NO</option>
                                    </select>
                                    @error('have_shop')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Search Result Kind') }}</label>
                                    <select name="search_result" wire:model="search_result" class="form-control">
                                        <option>-- select --</option>
                                        <option value="0">KM</option>
                                        <option value="1">Miles</option>
                                    </select>
                                    @error('search_result')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Search radius') }}</label>
                                    <input type="number" name="search_radius" wire:model="search_radius"
                                        value="{{ old('search_radius') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('search_radius')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <h4 class="p-3 text-info">App Settings</h4>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Currency Symbol') }}</label>
                                    <input type="text" name="currency_symbol" wire:model="currency_symbol"
                                        value="{{ old('currency_sysmbol') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('currency_symbol')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Currency Side') }}</label>
                                    <select name="currency_side" wire:model="currency_side" class="form-control">
                                        <option>-- select --</option>
                                        <option value="left">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                    @error('currency_side')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Currency Code') }}</label>
                                    <input type="text" name="currency_code" wire:model="currency_code"
                                        value="{{ old('currency_code') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('currency_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('App Direction') }}</label>
                                    <select name="app_direction" wire:model="app_direction" class="form-control">
                                        <option>-- select --</option>
                                        <option value="ltr">LTR</option>
                                        <option value="rtl">RTL</option>
                                    </select>
                                    @error('app_direction')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('User Login') }}</label>
                                    <select name="user_login" wire:model="user_login" class="form-control">
                                        <option>-- select --</option>
                                        <option value="0">Email & Password</option>
                                        <option value="1">Phone & Password</option>
                                        <option value="2">Phone & OTP</option>
                                    </select>
                                    @error('user_login')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('User Verify With') }}</label>
                                    <select name="user_verify" wire:model="user_verify" class="form-control">
                                        <option>-- select --</option>
                                        <option value="0">Phone Verifications</option>
                                        <option value="1">Email Verifications</option>
                                    </select>
                                    @error('user_verify')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('App Color') }}</label>
                                    <input type="color" name="app_color" wire:model="app_color"
                                        value="{{ old('app_color') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('app_color')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('App Status') }}</label>
                                    <select name="app_status" wire:model="app_status" class="form-control">
                                        <option>-- select --</option>
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </select>
                                    @error('app_status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Default Country Code without +') }}</label>
                                    <input type="number" name="default_country_code"
                                        wire:model="default_country_code" value="{{ old('default_country_code') }}"
                                        class="form-control" id="formrow-email-input">
                                    @error('default_country_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('FCM Token') }}</label>
                                    <textarea name="fcm_token" rows="3" wire:model="fcm_token" class="form-control" id="formrow-email-input"></textarea>
                                    @error('fcm_token')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Logo') }}</label>
                                    <input type="file" name="logo" wire:model="logo"
                                        value="{{ old('logo') }}" class="form-control" id="formrow-email-input">
                                    @if ($logo)
                                        <img src="{{ $logo->temporaryUrl() }}" alt="preview" width="150"
                                            height="100" class="p-2">
                                    @elseif ($logo_preview)
                                        <img src="{{ Storage::disk('spaces')->url($setting->logo) }}" alt="preview"
                                            width="150" height="100" class="p-2">
                                    @endif
                                    @error('logo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <h4 class="p-3 text-info">Social Informations</h4>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Facebook URL') }}</label>
                                    <input type="text" name="facebook_social" wire:model="facebook_social"
                                        value="{{ old('facebook_social') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('facebook_social')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Twitter URL') }}</label>
                                    <input type="text" name="twitter_social" wire:model="twitter_social"
                                        value="{{ old('twitter_social') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('twitter_social')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Instagram URL') }}</label>
                                    <input type="text" name="instagram_social" wire:model="instagram_social"
                                        value="{{ old('instagram_social') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('instagram_social')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('PlayStore URL') }}</label>
                                    <input type="text" name="playstore_social" wire:model="playstore_social"
                                        value="{{ old('playstore_social') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('playstore_social')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Appstore URL') }}</label>
                                    <input type="text" name="appstore_social" wire:model="appstore_social"
                                        value="{{ old('appstore_social') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('appstore_social')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Website URL') }}</label>
                                    <input type="text" name="website" wire:model="website"
                                        value="{{ old('website') }}" class="form-control"
                                        id="formrow-email-input">
                                    @error('website')
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

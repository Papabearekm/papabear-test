<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">View User</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <center>
                                    @if ($user->cover)
                                        <img src="{{ Storage::disk('spaces')->url($user->cover) }}" alt="cover preview" width="150"
                                            height="150" class="p-2">
                                    @else
                                        <img src="{{ asset('assets/images/dummy.jpeg') }}" alt="default" width="150"
                                            height="150" class="p-2">
                                    @endif
                                </center>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h4 class="p-3 text-info">Basic Details</h4>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">{{ __('First Name') }}</label>
                                <input
                                    value="{{ $user->first_name }}" class="form-control" id="formrow-email-input" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">{{ __('Last Name') }}</label>
                                <input
                                    value="{{ $user->last_name }}" class="form-control" id="formrow-email-input" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">{{ __('Email') }}</label>
                                <input value="{{ $user->email }}"
                                    class="form-control" id="formrow-email-input" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">{{ __('Country Code') }}</label>
                                <input value="{{ $user->country_code }}" class="form-control" id="formrow-email-input" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">{{ __('Mobile') }}</label>
                                <input value="{{ $user->mobile }}"
                                    class="form-control" id="formrow-email-input" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">{{ __('Gender') }}</label>
                                <select class="form-control" disabled>
                                    <option value="1" {{ $user->gender == "1" ? 'selected' : '' }}>Male</option>
                                    <option value="2" {{ $user->gender == "2" ? 'selected' : '' }}>Female</option>
                                    <option value="3" {{ $user->gender == "3" ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('users') }}" class="btn btn-primary w-md mt-3">{{ __('Back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

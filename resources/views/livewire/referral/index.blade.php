<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Referral Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
                        @csrf
                        <span class="text-danger">This information will be used as referral system. in order to disable the referral system. please choose status to deactive. </span>
                        <div class="row pt-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Referral title') }}</label>
                                    <input type="text" name="title" wire:model="title" value="{{ old('title') }}"
                                        class="form-control">
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Referral Message') }}</label>
                                    <textarea rows="5" name="message" wire:model="message" class="form-control"></textarea>
                                    @error('message')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Amount') }}</label>
                                    <input type="number" name="amount" wire:model="amount"
                                        value="{{ old('amount') }}" class="form-control" id="formrow-email-input">
                                    @error('amount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Limit') }}</label>
                                    <input type="number" name="limit" wire:model="limit" value="{{ old('limit') }}"
                                        class="form-control">
                                    @error('limit')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Who Recieved') }}</label>
                                    <select name="who_received" wire:model="who_received" class="form-control">
                                        <option value="1">Invitee</option>
                                        <option value="2">Who Redeem</option>
                                        <option value="3">Both Invitee & Who Redeem</option>
                                    </select>
                                    @error('who_received')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Status') }}</label>
                                    <select name="status" wire:model="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </select>
                                    @error('status')
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

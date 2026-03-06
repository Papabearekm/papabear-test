<div id="edit_modal" wire:ignore.self class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="formrow-email-input" class="form-label">{{ __('Content') }}</label>
                            <textarea name="content" rows="10" wire:model.live="content" class="form-control" id="formrow-email-input"></textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <form wire:submit.prevent="submit">
                        <input type="hidden" wire:model.live="page_id">
                        <div class="col-lg-12 text-end">
                            <button type="submit" class="btn btn-info" id="addtask">{{ __('Yes, Confirm') }}</button>
                            <button type="button" class="btn btn-danger" wire:click.prevent="reset_fields"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

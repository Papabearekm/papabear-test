<div id="delete_modal" wire:ignore.self class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <div class="col-lg-12 text-center">
                        <i class="mdi mdi-alert text-danger" style="font-size: 50px;"></i>
                        <h4>{{ __('Are You Sure ??') }}</h4>
                        <p style="font-weight: 300px;font-size:18px;">{{ __('This Banner Will Be Deleted Permenently') }}</p>
                    </div>
                </div>
                <div class="row">
                    <form wire:submit.prevent="destroy">
                        <input type="hidden" wire:model="banner_id">
                        <div class="col-lg-12 text-end">
                            <button type="submit" class="btn btn-info" id="addtask">{{ __('Yes, Confirm') }}</button>
                            <button type="button" class="btn btn-danger" wire:click.prevent="reset_fields" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
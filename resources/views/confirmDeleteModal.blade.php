<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title text-gray-800">{{ __('rooms.delete-room') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h4 class="text-lg font-medium leading-6 text-gray-800">
                        {{ __('rooms.confirm-deleting') }}
                    </h4>
                </div>
            </div>
                
            <div class="modal-footer">   
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('rooms.cancel') }}</button>
                <button :disabled="roomDeleteProcessing" @click="deleteRoomConfirm" type="button" class="d-flex align-items-center btn text-white bg-blue-500 bg-blue-600-hover">
                    <span v-show="roomDeleteProcessing"
                        class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                    <span class="ms-2">{{ __('rooms.delete-room') }}</span>
                </button>
            </div>
            
        </div>

    </div>
</div>
<div class="modal fade" id="set-moderators-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-gray-800">Set room moderators</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input @input="searchUsers" class="form-control" type="text" id="new_moder" list="usersList" />
                        <datalist id="usersList">
                        </datalist>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('rooms.cancel') }}</button>
                <button :disabled="updateRoomProcessing" @click="addNewModers" type="button"
                    class="d-flex align-items-center btn text-white bg-blue-500 bg-blue-600-hover">
                    <span v-show="updateRoomProcessing" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                    <span class="ms-2">Update room</span>
                </button>
            </div>

        </div>

    </div>
</div>

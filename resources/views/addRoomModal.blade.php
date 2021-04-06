<div class="modal fade" id="addRoomModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="addRoomModalLabel">{{ __('rooms.create-room-title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form v-on:submit.prevent="createRoom" class="p-4">
                    <div class="row alert alert-danger" role="alert" v-show="errors.request">
                        Request error!
                    </div>
                    <div class="row">
                        <label for="inputRoomCreateName" class="form-label">{{ __('rooms.room-title-label') }}</label>
                        <input :disabled="roomCreateProcessing" v-model="roomName" :class="{ 'is-invalid': errors.name }"
                            type="text" class="form-control" id="inputRoomCreateName">
                        <div class="invalid-feedback">
                            <span v-for="error in errors.name">@{{ error }}</span>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('rooms.cancel') }}</button>
                <button :disabled="roomCreateProcessing" @click="createRoom"
                    type="button" class="d-flex align-items-center btn text-white bg-blue-500 bg-blue-600-hover">
                    <span v-show="roomCreateProcessing"
                        class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                    <span class="ms-2">{{ __('rooms.room-create') }}</span>
                </button>
            </div>

        </div>
    </div>
</div>
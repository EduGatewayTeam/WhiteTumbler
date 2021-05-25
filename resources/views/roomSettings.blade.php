<div class="modal fade" id="roomSettingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-gray-800">{{ __('rooms.room-settings') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="isUsersMuteAfterJoin"/>
                    <label class="form-check-label" for="isUsersMuteAfterJoin">Mute users when they join</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="isRequireModeratorApproval"/>
                    <label class="form-check-label" for="isRequireModeratorApproval">Require moderator approval before joining</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="isAllowAnyUserToStartMeeting"/>
                    <label class="form-check-label" for="isAllowAnyUserToStartMeeting">Allow any user to start this meeting</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="isAllUsersModerators"/>
                    <label class="form-check-label" for="isAllUsersModerators">All users join as moderators</label>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('rooms.cancel') }}</button>
                <button :disabled="updateRoomProcessing" type="button"
                    class="d-flex align-items-center btn text-white bg-blue-500 bg-blue-600-hover">
                    <span v-show="updateRoomProcessing" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                    <span class="ms-2">{{ __('rooms.update-room') }}</span>
                </button>
            </div>

        </div>

    </div>
</div>

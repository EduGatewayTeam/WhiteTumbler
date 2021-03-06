<div class="modal fade" id="roomPage" tabindex="-1" aria-labelledby="roomPageLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div v-if="activeRoom !== null" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" @click="updateRoom" id="roomPageLabel">@{{ activeRoom . name }}</h5>
                <button type="button" class="btn-close text-gray-50" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div v-for="(item, index) in activeRoom.schedule">

                    <div :id="`${item.day_type}_${getWeekDay(item.week_day)}`" class="p-2 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div v-if="isMeetingActive(item.time_start, item.time_end, item.week_day) > 0" class="spinner-grow spinner-grow-sm text-danger"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            <i v-else class="fa fa-podcast" style="color: #2563eb !important"></i>
                            <span class="ms-3" style="font-weight: bold !important;">@{{ item.day_type + ' ' + getWeekDay(item.week_day) }}</span>
                            <span class="ms-3">@{{ item . time_start }}</span>
                            <span>&#160—&#160</span>
                            <span>@{{ item . time_end }}</span>
                        </div>

                        <div class="d-flex justify-content-end">

                            <delete-meeting :meetingWeekDay='`${item.week_day}`' :meetingDayType='`${item.day_type}`' :meetingWeekDayName='`${getWeekDay(item.week_day)}`'></delete-meeting>

                            <a :href="`/room/${activeRoom.id}/join`"
                                class="btn p-2 lh-1 rounded-circle bg-blue-50 bg-blue-400-hover"
                                v-b-popover.hover.top="'Go to meeting'"
                            >
                                <svg v-if="isMeetingActive(item.time_start, item.time_end, item.week_day) == 0" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
                                </svg>

                                <svg v-else-if="isMeetingActive(item.time_start, item.time_end, item.week_day) > 0" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" fill="currentColor" class="bi bi-arrow-right-circle"
                                    viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>

                <div v-if="activeRoom.schedule.length == 0">
                    <span class="fs-4 text-gray-400">
                        {{ __('rooms.no-meetings') }}
                    </span>
                </div>

                <div id="create-meeting-form" @click="updateRoom" class="mt-2 d-flex align-items-center">

                    <schedule-meetings :roomId="activeRoom.id"></schedule-meetings>

                </div>

            </div>
        </div>
    </div>
</div>

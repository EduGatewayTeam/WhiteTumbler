<template>
    <span
        @click="deleteMeeting"
        v-b-popover.hover.top="'Delete meeting'"
        class="btn p-2 lh-1 rounded-circle bg-blue-50 bg-blue-400-hover me-1"
    >
        <span
            v-if="meetingDeleteProcessing"
            class="spinner-border spinner-size-24"
            role="status"
        >
            <span class="visually-hidden">Loading...</span>
        </span>

        <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            fill="currentColor"
            class="bi bi-x-circle"
            viewBox="0 0 16 16"
        >
            <path
                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"
            />
            <path
                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"
            />
        </svg>
    </span>
</template>

<script>
import { VBPopover } from "bootstrap-vue";
import api from "../api";
import state from "../state";

export default {
    props: {
        meetingWeekDay: String,
        meetingDayType: String,
        meetingWeekDayName: String,
    },
    directives: {
        "b-popover": VBPopover
    },
    data() {
        return {
            meetingDeleteProcessing: false
        };
    },
    methods: {
        async deleteMeeting() {
            if (this.meetingDeleteProcessing) {
                return;
            }

            let currentState = state.getState();
            let schedule = [...currentState.activeRoom.schedule];

            this.meetingDeleteProcessing = true;
            
            await schedule.forEach( (item, index, object) => {
                if (item?.week_day == this.meetingWeekDay && item?.day_type == this.meetingDayType) {
                    object.splice(index, 1);
                }
            });

            console.log('final schedule: ', schedule);

            let response = await api.patch(`/room/${currentState.activeRoom.id}`, {
                schedule
            });

            if (response.data.errors) {
                this.$toast.error(`The meetins was NOT updated.`);
            } else { 
                let meetingId = `#${this.meetingDayType}_${this.meetingWeekDayName}`;
                console.log('meet_id: ', meetingId);
                this.$toast.success(`The room schedule was updated.`);
                $(meetingId).remove();

                let activeRoom = { ...currentState.activeRoom, schedule: schedule };  
                let rooms = currentState.rooms.map ( room => { return activeRoom.id == room.id ? activeRoom : room})
                state.dispatch({
                    type: "UPDATE_ROOM_SCHEDULE",
                    data: { activeRoom, rooms }
                });
            }
            
            this.meetingDeleteProcessing = false;
        }
    }
};
</script>

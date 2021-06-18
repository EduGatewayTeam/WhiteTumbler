<script>
import jstz from "jstz"; // import lib for timezone detection
import moment from "moment"; // import lib for manipulating dates
import { VBPopover } from "bootstrap-vue"; // import lib for pop-overs
import api from "../api"; // import instance of axios
import state from "../state"; // import app state

window.state = state;

export default {
    template: "#rooms-template",
    props: {
        roomsInit: Array
    },
    directives: {
        "b-popover": VBPopover
    },
    data() {
        if (!sessionStorage.getItem("timezone")) {
            let tz = jstz.determine() || "UTC";
            sessionStorage.setItem("timezone", tz.name());
        }

        let currTz = sessionStorage.getItem("timezone");

        return {
            rooms: this.roomsInit,
            roomCreateProcessing: false,
            roomDeleteProcessing: false,
            roomName: "",
            errors: {
                request: "",
                roomName: ""
            },
            activeRoom: null,
            activeRoomIndex: null,
            newMeetingProcessing: false,
            meetingName: "",
            updateRoomProcessing: false,
            dateTimeRange: null,
            currTz: currTz,
            weekDays: [
                "monday",
                "tuesday",
                "wednesday",
                "thursday",
                "friday",
                "saturday",
                "sunday"
            ]
        };
    },
    mounted() {
        console.log(this.roomsInit);
        state.dispatch({
            type: "SET_ROOMS",
            data: { rooms: this.roomsInit }
        });
    },
    methods: {
        setSelectedRoomIndex(index) {
            state.dispatch({
                type: "SET_ACTIVE_ROOM",
                data: { selectedRoomIndex: index }
            });
        },
        createRoom() {
            if (!this.roomName) {
                return;
            }

            this.roomCreateProcessing = true;
            api.post("/rooms", {
                name: this.roomName
            })
                .then(response => {
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        this.errors = {};
                        this.roomName = "";
                        this.rooms.push(response.data);
                        $("#add-room-modal").modal("hide");
                    }
                })
                .catch(error => {
                    this.errors = {
                        request: error
                    };
                    this.$toast.error(
                        `Something was wrong with room creation.`
                    );
                })
                .finally(() => {
                    this.roomCreateProcessing = false;
                });
        },
        openRoom(roomIndex) {
            this.activeRoom = this.rooms[roomIndex];
            this.activeRoomIndex = roomIndex;
            $("#roomPage").modal("show");
        },
        openRoomSettings(roomIndex) {
            $("#room-settings-modal").modal("show");
            this.activeRoomIndex = roomIndex;
        },

        deleteRoom(roomIndex) {
            $("#confirm-delete-modal").modal("show");
            this.activeRoomIndex = roomIndex;
        },
        deleteRoomConfirm() {
            this.roomDeleteProcessing = true;

            api.delete(`/rooms/${this.rooms[this.activeRoomIndex].id}`)
                .then(response => {
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        this.errors = {};
                        this.rooms.splice(this.activeRoomIndex, 1);
                        $("#confirm-delete-modal").modal("hide");
                        this.$toast.success(`The room was deleted.`);
                    }
                })
                .catch(error => {
                    this.errors = {
                        request: error
                    };
                    this.$toast.error(`The room was not deleted.`);
                })
                .finally(() => {
                    $("#confirm-delete-modal").modal("hide");
                    this.activeRoomIndex = null;
                    this.roomDeleteProcessing = false;
                });
        },
        getDataTimeRange(dateTimeRange) {
            this.dateTimeRange = dateTimeRange;
        },
        async updateSchedule() {
            let currentState = state.getState();
            let schedule = [...currentState.activeRoom.schedule];
            this.newMeetingProcessing = true;

            await currentState.schedule.forEach( (day, dayIndex) => {

                if (day.even) {

                    schedule.forEach( (item, index, object) => {
                        if (item?.week_day == dayIndex) {
                            object.splice(index, 1);
                        }
                    });

                    schedule.push({
                        day_type: "even",
                        week_day: dayIndex,
                        time_start: this.dateLocalization(
                            day.even[0],
                            "hh:mm:ss"
                        ),
                        time_end: this.dateLocalization(day.even[1], "hh:mm:ss")
                    });
                }

                if (day.odd) {

                    schedule.forEach( (item, index, object) => {
                        if (item?.week_day == dayIndex) {    
                            object.splice(index, 1);
                        } 
                    });

                    schedule.push({
                        day_type: "odd",
                        week_day: dayIndex,
                        time_start: this.dateLocalization(
                            day.odd[0],
                            "hh:mm:ss"
                        ),
                        time_end: this.dateLocalization(day.odd[1], "hh:mm:ss")
                    });
                }
            });

            console.log('final schedule: ', schedule);

            let response = await api.patch(`/room/${this.activeRoom.id}`, {
                schedule
            });

            if (response.data.errors) {
                this.$toast.error(`The schedule was NOT updated.`);
            } else this.$toast.success(`The room schedule was updated.`);
            
            let activeRoom = { ...this.activeRoom, 'schedule':  schedule};
            state.dispatch( { 'type': 'SET_ACTIVE_ROOM_SCHEDULE', 'data': { activeRoom } } );
            
            currentState = state.getState();

            this.activeRoom = currentState.activeRoom;
            console.log('new active room: ', currentState.activeRoom)
            this.rooms = currentState.rooms;
            this.newMeetingProcessing = false;
            $("#roomPage").modal("hide");
        },
        dateLocalization(meeting_date, output_format = "DD.MM.YY HH:mm") {
            let date = moment(meeting_date).format(output_format);
            return date;
        },
        isMeetingActive(time_start, time_end) {
            let now = new Date();
            let currentDate = `${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`;

            return currentDate > time_start && currentDate < time_end ? 1 : 0;
        },
        getWeekDay(weekDay) {
            let weekDayIndex = parseInt(weekDay);
            return this.weekDays[weekDayIndex];
        }
    }
};
</script>

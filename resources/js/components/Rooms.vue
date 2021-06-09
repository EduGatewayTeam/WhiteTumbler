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
            currTz: currTz
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
            this.meetings = this.checkMeetingActivation(
                this.rooms[roomIndex].meetings
            );
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
        addMeeting() {
            if (!this.meetingName) {
                return;
            }

            debugger

            let sessions = [];
            let shedule = state.getState().shedule;

            if (shedule) {
                
                shedule.forEach( (day, index) => {
                    
                    day.even ?
                        sessions.push({
                            day_type: "even",
                            week_day: index + 1,
                            time_start: day.even[0],
                            time_end: day.even[1]
                        })
                    :
                        null;

                    day.odd ?
                        sessions.push({
                            day_type: "odd",
                            week_day: index + 1,
                            time_start: day.odd[0],
                            time_end: day.odd[1]
                        })
                    :
                        null;
                });
            }

            this.newMeetingProcessing = true;
            let activationDate = this.dateTimeRange
                ? this.dateTimeRange[0]
                : null;
            let deactivationDate = this.dateTimeRange
                ? this.dateTimeRange[1]
                : null;
            api.post("/meetings", {
                roomId: this.activeRoom.id,
                name: this.meetingName,
                sessions,
                activationDate,
                deactivationDate
            })
                .then(response => {
                    if (response.data.errors) {
                        //this.errors = response.data.errors
                    } else {
                        this.activeRoom.meetings.push(response.data);
                        this.meetings = this.checkMeetingActivation(
                            this.activeRoom.meetings
                        );
                        Vue.set(
                            this.rooms,
                            this.activeRoomIndex,
                            this.activeRoom
                        );
                    }
                })
                .catch(error => {
                    this.errors = {
                        request: error
                    };
                })
                .finally(() => {
                    this.newMeetingProcessing = false;
                    this.meetingName = "";
                });
        },
        checkMeetingActivation(meetings) {
            meetings.forEach(function(meeting, index) {
                let now = new Date(),
                    meetingActivateDate = new Date(meeting.activateAt.date);
                if (meeting.deactivateAt !== null) {
                    let meetingDeactivateDate = new Date(
                        meeting.deactivateAt.date
                    );
                    if (now.getTime() >= meetingActivateDate.getTime()) {
                        if (now.getTime() < meetingDeactivateDate.getTime()) {
                            meeting.isActive = true;
                        } else {
                            meeting.isActive = -1;
                        }
                    } else {
                        meeting.isActive = false;
                    }
                } else {
                    meeting.isActive =
                        now.getTime() >= meetingActivateDate.getTime();
                }
            });

            meetings.sort(function(a, b) {
                return (
                    new Date(a.activateAt.date) - new Date(b.activateAt.date)
                );
            });

            return meetings;
        },
        dateLocalization(meeting_date, output_format = "DD.MM.YY HH:mm") {
            let date = moment(meeting_date).format(output_format);
            return date;
        }
    }
};
</script>

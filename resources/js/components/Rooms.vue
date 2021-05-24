<script>
import jstz from 'jstz'
import moment from 'moment'
import axios from 'axios'
import { VBPopover } from "bootstrap-vue";

const api = axios.create()
api.defaults.withCredentials = true
api.defaults.headers.common['Content-Type'] = 'application/json'

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
            var tz = jstz.determine() || "UTC";
            sessionStorage.setItem("timezone", tz.name());
        }
        var currTz = sessionStorage.getItem("timezone");

        return {
            rooms: this.roomsInit,
            roomCreateProcessing: false,
            roomDeleteProcessing: false,
            roomName: "",
            delitingRoomIndex: null,
            errors: {
                request: "",
                roomName: ""
            },
            activeRoom: null,
            activeRoomIndex: null,
            newMeetingProcessing: false,
            meetingName: "",
            dateTimeRange: null,
            currTz: currTz
        };
    },
    mounted() {
        console.log(this.roomsInit);
    },
    methods: {
        createRoom() {
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
                        $("#addRoomModal").modal("hide");
                    }
                    console.log(response);
                })
                .catch(error => {
                    this.errors = {
                        request: error
                    };
                })
                .then(() => {
                    this.roomCreateProcessing = false;
                });
        },
        openRoom(roomIndex) {
            this.activeRoom = this.rooms[roomIndex];
            this.activeRoomIndex = roomIndex;
            $("#roomPage").modal("show");
            console.log("Room name: " + this.activeRoom.name);
            this.meetings = this.checkMeetingActivation(
                this.rooms[roomIndex].meetings
            );
        },

        // refactor two functions in one
        deleteRoom(roomIndex) {
            $("#confirmDeleteModal").modal("show");
            this.delitingRoomIndex = roomIndex;
        },
        deleteRoomConfirm() {
            this.roomDeleteProcessing = true;
            console.log(this.delitingRoomIndex);

            api.delete(`/rooms/${this.rooms[this.delitingRoomIndex].id}`)
                .then(response => {
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    } else {
                        this.errors = {};
                        this.rooms.splice(this.delitingRoomIndex, 1);
                        $("#confirmDeleteModal").modal("hide");
                        this.$toast.success(`The room was deleted.`);
                    }
                    console.log("response: ", response);
                })
                .catch(error => {
                    this.errors = {
                        request: error
                    };
                    this.$toast.error(`The room was not deleted.`);
                    console.log(error);
                })
                .finally(() => {
                    $("#confirmDeleteModal").modal("hide");
                    this.delitingRoomIndex = null;
                    this.roomDeleteProcessing = false;
                });
        },
        getDataTimeRange(dateTimeRange) {
            console.log("getDataTimeRange: ", dateTimeRange);
            this.dateTimeRange = dateTimeRange;
        },
        addMeeting() {
            this.newMeetingProcessing = true;
            console.log("addMeeting(this): ", this);
            let activationDate = this.dateTimeRange
                ? this.dateTimeRange[0]
                : null;
            let deactivationDate = this.dateTimeRange
                ? this.dateTimeRange[1]
                : null;
            api.post("/meetings", {
                roomId: this.activeRoom.id,
                name: this.meetingName,
                activationDate,
                deactivationDate
            })
                .then(response => {
                    if (response.data.errors) {
                        // this.errors = response.data.errors
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
                        // this.errors = {}
                        this.meetingName = "";
                    }
                    console.log(response);
                })
                .catch(error => {
                    this.errors = {
                        request: error
                    };
                })
                .then(() => {
                    this.newMeetingProcessing = false;
                });
        },
        checkMeetingActivation(meetings) {
            meetings.forEach(function(meeting, index) {
                let now = new Date(),
                    meetingActivateDate = new Date(meeting.activateAt.date);
                if (meeting.deactivateAt !== null) {
                    var meetingDeactivateDate = new Date(
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
            // var momentTime = moment(date + "Z");
            // var tzTime = momentTime.tz(this.currTz);
            // var format_date = tzTime.format(output_format);
            // return format_date;
        }
    }
};
</script>

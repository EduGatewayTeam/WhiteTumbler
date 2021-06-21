<script>
import jstz from "jstz"; // import lib for timezone detection
import moment from "moment"; // import lib for manipulating dates
import { VBPopover } from "bootstrap-vue"; // import lib for pop-overs
import api from "../api"; // import instance of axios
import state from "../state"; // import app state

window.state = state;

const Rooms = {
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
            ],
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
        },
        updateRoom() {

            let currentState = state.getState();
            let updatedRooms = currentState.rooms;
                        
            this.rooms = updatedRooms ? updatedRooms : null;
            this.activeRoom = updatedRooms[this.activeRoomIndex];
        },
    }
};

export default Rooms;
</script>

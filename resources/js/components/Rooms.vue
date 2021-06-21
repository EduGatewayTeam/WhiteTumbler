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
            selectedValue: null,
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
        openModersModal(roomIndex) {
            this.activeRoom = this.rooms[roomIndex];
            this.activeRoomIndex = roomIndex;
            $("#set-moderators-modal").modal("show");
        },
        async addNewModers() {
            this.updateRoomProcessing = true;

            let userId = "";
            let optionList = document.getElementById("usersList").childNodes;
            let checkedValue = document.getElementById("new_moder").value;
            console.log('checkedValue: ', checkedValue);
            await optionList.forEach(node => {
                console.log('node.innerText: ', node.innerText);
                node.innerText == checkedValue ? (userId = node.id) : "";
            });
            console.log("userId: ", userId);
            api.post(`/room/${this.activeRoom.id}/add_moderator`, { 'moderator_id': userId }
            ).then(response => {
                if (response.data.errors) {
                    this.errors = response.data.errors;
                } else {
                    this.$toast.success(`Moder was aded.`);
                }
            });
            this.updateRoomProcessing = false;
        },
        searchUsers(e) {
            let userName = e.target.value;
            console.log("Search user name: ", userName);
            $("#usersList").html(`<option>Loading...</option>`);
            api.post("/search", {
                query: userName
            }).then(response => {
                if (response.data.errors) {
                    this.errors = response.data.errors;
                } else {
                    console.log("search responce: ", response.data);
                    let optionsValues = response.data.map(user => {
                        return `<option id="${user.id}">${user.surname} ${user.name} ${user.patronymic}</option>`;
                    });
                    let optionsString = optionsValues.join();
                    $("#usersList").html(optionsString);
                }
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
        isMeetingActive(time_start, time_end, week_day) {
            let now = new Date();
            if (now.getDay() - 1 != week_day) 
                return false;

            let hours = now.getHours() < 10 ? '0' + now.getHours() :now.getHours()
            let currentDate = `${hours}:${now.getMinutes()}:${now.getSeconds()}`;


            return currentDate > time_start && currentDate < time_end ? true : false;
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
        }
    }
};

export default Rooms;
</script>

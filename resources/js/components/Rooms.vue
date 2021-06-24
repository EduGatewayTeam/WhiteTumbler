<script>
import jstz from "jstz"; // импорт библиотеки для работы с временной зоной
import moment from "moment"; // импорт библиотеки для манипулирования датой
import { VBPopover } from "bootstrap-vue"; // импортируем библиотеку для отображения всплывающих окон
import api from "../api"; // импортируем HTTP-клиент
import state from "../state"; // импортируем состояние приложения

// для доступа из консоли браузера
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
        // установка активной комнаты
        setSelectedRoom(activeRoom) {
            state.dispatch({
                type: "SET_ACTIVE_ROOM",
                data: { activeRoom }
            });
        },
        // открываем окно для добавления модеров
        openModersModal(roomIndex) {
            this.activeRoom = this.rooms[roomIndex];
            this.activeRoomIndex = roomIndex;
            $("#set-moderators-modal").modal("show");
        },
        sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        // добавление модера
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
            if (!userId)
            {
                this.updateRoomProcessing = false;
                return
            }
                
            await api.post(`/room/${this.activeRoom.id}/add_moderator`, { 'moderator_id': userId })

            this.$toast.success(`Moder was aded.`);
            
            this.updateRoomProcessing = false;
        },
        async setSettings(){
            this.updateRoomProcessing = true;
            await this.sleep(1500);
            this.$toast.success(`Settings was updated.`);
            this.updateRoomProcessing = false;
        },
        // поиск пользователей
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
        // создание комнаты
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
        // открытие модального окна управлени комнатой
        openRoom(roomIndex) {
            this.activeRoom = this.rooms[roomIndex];
            this.activeRoomIndex = roomIndex;
            $("#roomPage").modal("show");
        },
        // открытие настроек комнаты
        openRoomSettings(roomIndex) {
            $("#room-settings-modal").modal("show");
            this.activeRoomIndex = roomIndex;
        },
        // удаление комнаты
        deleteRoom(roomIndex) {
            $("#confirm-delete-modal").modal("show");
            this.activeRoomIndex = roomIndex;
        },
        // подтверждение удаления
        deleteRoomConfirm() {
            this.roomDeleteProcessing = true;
            let roomId = state.getState().activeRoom.id;
            api.delete(`/rooms/${roomId}`)
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
        // получение интервала времени
        getDataTimeRange(dateTimeRange) {
            this.dateTimeRange = dateTimeRange;
        },
        // форматирование даты
        dateLocalization(meeting_date, output_format = "DD.MM.YY HH:mm") {
            let date = moment(meeting_date).format(output_format);
            return date;
        },
        // проверка активна ли встреча
        isMeetingActive(time_start, time_end, week_day) {
            let now = new Date();
            if (now.getDay() - 1 != week_day) 
                return false;

            let hours = now.getHours() < 10 ? '0' + now.getHours() :now.getHours()
            let currentDate = `${hours}:${now.getMinutes()}:${now.getSeconds()}`;


            return currentDate > time_start && currentDate < time_end ? true : false;
        },
        // получить название дня недели
        getWeekDay(weekDay) {
            let weekDayIndex = parseInt(weekDay);
            return this.weekDays[weekDayIndex];
        },
        // обновление состояния компоненты
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

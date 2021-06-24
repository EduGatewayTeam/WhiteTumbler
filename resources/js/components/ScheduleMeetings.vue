<script>
import DatePicker from "vue2-datepicker"; // ипорт компонента для выбора интервала времени
import { VBPopover } from "bootstrap-vue"; // импортируем библиотеку для отображения всплывающих окон
import moment from "moment"; // импортируем библиотеку для работы с временем
import api from "../api"; // импортируем HTTP-клиент
import state from "../state"; // импортируем состояние приложения

// импортируем стили для компоненты выбора времени
import "vue2-datepicker/index.css";

export default {
    components: { DatePicker },
    data() {
        return {
            sessions: null,
            isVisible: false,
            newMeetingProcessing: false,
            schedule: state.getState().schedule
        };
    },
    props: {
        roomId: String,
    },
    directives: {
        "b-popover": VBPopover
    },
    methods: {
        changeTimePickerVisibility(id) {
            let timeRangePicker = document.getElementById(id);
            if (timeRangePicker.style.display !== "none") {
                timeRangePicker.setAttribute(
                    "style",
                    "display:none !important"
                );
            } else {
                timeRangePicker.style.display = "flex";
            }
        },
        sendData: function() {
            this.$emit("sendDataTimeRange", this.dataTimeRange);
        },
        dateLocalization(meeting_date, output_format = "DD.MM.YY HH:mm") {
            let date = moment(meeting_date).format(output_format);
            return date;
        },
        async updateSchedule() {
            let currentState = state.getState();
            let schedule = currentState.activeRoom.schedule ? [...currentState.activeRoom.schedule] : [];
            this.newMeetingProcessing = true;

            await currentState.schedule.forEach((day, dayIndex) => {
                if (day.even) {
                    schedule.forEach((item, index, object) => {
                        if (
                            item?.week_day == dayIndex &&
                            item?.day_type == "even"
                        ) {
                            object.splice(index, 1);
                        }
                    });

                    schedule.push({
                        day_type: "even",
                        week_day: dayIndex,
                        time_start: this.dateLocalization(
                            day.even[0],
                            "HH:mm:ss"
                        ),
                        time_end: this.dateLocalization(day.even[1], "HH:mm:ss")
                    });
                }

                if (day.odd) {
                    schedule.forEach((item, index, object) => {
                        if (
                            item?.week_day == dayIndex &&
                            item?.day_type == "odd"
                        ) {
                            object.splice(index, 1);
                        }
                    });

                    schedule.push({
                        day_type: "odd",
                        week_day: dayIndex,
                        time_start: this.dateLocalization(
                            day.odd[0],
                            "HH:mm:ss"
                        ),
                        time_end: this.dateLocalization(day.odd[1], "HH:mm:ss")
                    });
                }
            });

            console.log("final schedule: ", schedule);

            let response = await api.patch(`/room/${currentState.activeRoom.id}`, {
                schedule
            });

            if (response.data.errors) {
                this.$toast.error(`The schedule was NOT updated.`);
            } else this.$toast.success(`The room schedule was updated.`);

            let activeRoom = { ...currentState.activeRoom, schedule: schedule };
            let rooms = currentState.rooms.map ( room => { return activeRoom.id == room.id ? activeRoom : room})
            state.dispatch({
                type: "UPDATE_ROOM_SCHEDULE",
                data: { activeRoom, rooms }
            });
            state.dispatch({
                type: "SET_ACTIVE_ROOM",
                data: { activeRoom }
            });

            await state.dispatch({ type: 'SET_DEFAULT_SCHEDULE' });
            // update local and global state
            this.schedule = this.schedule.map( day => { return { ...day, odd: null, even: null } });
            this.newMeetingProcessing = false;
            document.getElementById('dateTimeSVG').click();
        },
        changeVisibility() {
            let createMeetingForm = document.getElementById(
                "create-meeting-form"
            );

            if (this.isVisible) {
                // если было раскрыто
                createMeetingForm.className = "mt-2 d-flex align-items-center";
            } else {
                // если было свернуто
                createMeetingForm.className =
                    "mt-2 d-flex flex-column justify-content-around";
            }

            this.isVisible = !this.isVisible;
        },
        setSchedule() {
            state.dispatch({
                type: "SET_ROOM_SCHEDULE",
                data: { schedule: this.schedule }
            });
        }
    }
};
</script>

<template>
    <div>
        <div>
            <div @click="changeVisibility" class="m-2" id="dateTimeSVG">
                <svg
                    v-b-popover.hover.left="'Schedule meetings'"
                    class="dateTimeIcon"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 1024 1024"
                    width="1.5em"
                    height="1.5em"
                >
                    <path
                        d="M940.218182 107.054545h-209.454546V46.545455h-65.163636v60.50909H363.054545V46.545455H297.890909v60.50909H83.781818c-18.618182 0-32.581818 13.963636-32.581818 32.581819v805.236363c0 18.618182 13.963636 32.581818 32.581818 32.581818h861.090909c18.618182 0 32.581818-13.963636 32.581818-32.581818V139.636364c-4.654545-18.618182-18.618182-32.581818-37.236363-32.581819zM297.890909 172.218182V232.727273h65.163636V172.218182h307.2V232.727273h65.163637V172.218182h176.872727v204.8H116.363636V172.218182h181.527273zM116.363636 912.290909V442.181818h795.927273v470.109091H116.363636z"
                    ></path>
                </svg>
            </div>

            <div
                v-if="isVisible"
                class="d-flex flex-column justify-content-around"
            >
                <div class="d-flex weekDays-selector m-1">
                    <span class="m-1">ODD WEEK: </span>

                    <div v-for="day in schedule" :key="`odd-${day.dayname}`">
                        <input
                            type="checkbox"
                            @click="
                                changeTimePickerVisibility(
                                    `odd-time-range-${day.dayname}`
                                )
                            "
                            :id="`odd-weekday-${day.dayname.slice(0, 3)}`"
                            class="weekday"
                        />
                        <label
                            :for="`odd-weekday-${day.dayname.slice(0, 3)}`"
                            >{{ day.dayname[0].toUpperCase() }}</label
                        >
                    </div>
                </div>

                <div class="d-flex weekDays-selector">
                    <span class="m-2">EVEN WEEK: </span>

                    <div v-for="day in schedule" :key="`even-${day.dayname}`">
                        <input
                            type="checkbox"
                            @click="
                                changeTimePickerVisibility(
                                    `even-time-range-${day.dayname}`
                                )
                            "
                            :id="`even-weekday-${day.dayname.slice(0, 3)}`"
                            class="weekday"
                        />
                        <label
                            :for="`even-weekday-${day.dayname.slice(0, 3)}`"
                            >{{ day.dayname[0].toUpperCase() }}</label
                        >
                    </div>
                </div>
            </div>

            <div v-if="isVisible" class="col-md-12 form-group p-1">
                <div
                    class="d-flex"
                    :id="`even-time-range-${day.dayname}`"
                    v-for="day in schedule"
                    :key="`even-time-range-${day.dayname}`"
                    style="display: none !important;"
                >
                    <label class="m-1 p-1">Even {{ day.dayname }}: </label>
                    <date-picker
                        v-if="isVisible"
                        @input="setSchedule"
                        v-model="day.even"
                        type="time"
                        range
                        placeholder="Select time when room is active"
                    ></date-picker>
                </div>
                <div
                    class="d-flex"
                    :id="`odd-time-range-${day.dayname}`"
                    v-for="day in schedule"
                    :key="`odd-time-range-${day.dayname}`"
                    style="display: none !important;"
                >
                    <label class="m-1 p-1">Odd {{ day.dayname }}: </label>
                    <date-picker
                        v-if="isVisible"
                        v-model="day.odd"
                        @input="setSchedule"
                        type="time"
                        range
                        placeholder="Select time when room is active"
                    ></date-picker>
                </div>
            </div>
        </div>
        <div>
            <button
                @click="updateSchedule"
                type="button"
                class="btn p-2 m-2 border-0 bg-blue-500 bg-blue-600-hover text-white lh-1"
            >
                <span
                    v-if="newMeetingProcessing"
                    class="spinner-border spinner-size-24"
                    role="status"
                >
                    <span class="visually-hidden">Loading...</span>
                </span>
                Update schedule
            </button>
            <a
                :href="`room/${this.roomId}/join`"
                type="button"
                class="btn p-2 m-2 border-0 bg-red-500 bg-red-600-hover text-white lh-1"
            >
                Start room
            </a>
        </div>
    </div>
</template>

<style>
.weekDays-selector input {
    display: none !important;
}

.weekDays-selector input[type="checkbox"] + label {
    display: inline-block;
    border-radius: 6px;
    background: #dddddd;
    height: 30px;
    width: 30px;
    margin-right: 3px;
    line-height: 30px;
    text-align: center;
    cursor: pointer;
}

.weekDays-selector input[type="checkbox"]:checked + label {
    background: #2563eb;
    color: #ffffff;
}
</style>

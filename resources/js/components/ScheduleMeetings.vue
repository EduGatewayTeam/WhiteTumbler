<script>
import DatePicker from "vue2-datepicker";
import { VBPopover } from "bootstrap-vue";

import "vue2-datepicker/index.css";

import state from "../state";

export default {
    components: { DatePicker },
    data() {
        return {
            sessions: null,
            isVisible: false,
            schedule: [
                {"dayname": "monday", "even": null, "odd": null},
                {"dayname": "tuesday", "even": null, "odd": null},
                {"dayname": "wednesday", "even": null, "odd": null},
                {"dayname": "thursday", "even": null, "odd": null},
                {"dayname": "friday", "even": null, "odd": null},
                {"dayname": "saturday", "even": null, "odd": null},
            ]
        };
    },
    directives: {
        "b-popover": VBPopover
    },
    methods: {
        changeTimePickerVisibility(id){
            let timeRangePicker = document.getElementById(id);
            if(timeRangePicker.style.display !== 'none') {
                timeRangePicker.setAttribute('style', 'display:none !important');
            }
            else {
                timeRangePicker.style.display = 'flex';
            }
        },
        sendData: function() {
            this.$emit("sendDataTimeRange", this.dataTimeRange);
        },
        changeVisibility() {
            let createMeetingForm = document.getElementById("create-meeting-form");
            if (this.isVisible){
                // если было раскрыто
                createMeetingForm.className = "mt-2 d-flex align-items-center"
            }
            else {
                // если было свернуто
                createMeetingForm.className = "mt-2 d-flex flex-column justify-content-around"
            }

            if (this.isVisible) {
                state.getState().schedule = [];
                this.schedule.forEach( (day) => { day.even = null; day.odd = null; } );
            }
            console.log(state.getState());
            this.isVisible = !this.isVisible;
        },
        setSchedule(){
            console.log(this.schedule);
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
        <div
            @click="changeVisibility"
            class="m-2"
        >
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

        <div v-if="isVisible" class="d-flex flex-column justify-content-around">
            <div class="d-flex weekDays-selector m-1">
                
                <span class="m-1">ODD WEEK: </span>
                
                <div v-for="(day) in schedule" :key="`odd-${day.dayname}`">
                    <input type="checkbox" @click="changeTimePickerVisibility(`odd-time-range-${day.dayname}`)" :id="`odd-weekday-${day.dayname.slice(0, 3)}`" class="weekday" />
                    <label :for="`odd-weekday-${day.dayname.slice(0, 3)}`">{{ day.dayname[0].toUpperCase() }}</label>
                </div>

            </div>

            <div class="d-flex weekDays-selector">
                <span class="m-2">EVEN WEEK: </span>
                
                <div v-for="(day) in schedule" :key="`even-${day.dayname}`">
                    <input type="checkbox" @click="changeTimePickerVisibility(`even-time-range-${day.dayname}`)" :id="`even-weekday-${day.dayname.slice(0, 3)}`" class="weekday" />
                    <label :for="`even-weekday-${day.dayname.slice(0, 3)}`">{{ day.dayname[0].toUpperCase() }}</label>
                </div>

            </div>

        </div>


        <div v-if="isVisible" class="col-md-12 form-group p-1">
            <div 
                class="d-flex" 
                :id="`even-time-range-${day.dayname}`" 
                v-for="(day) in schedule" 
                :key="`even-time-range-${day.dayname}`"
                style="display: none !important;"
            >
                <label class="m-1 p-1">Even {{day.dayname}}: </label>
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
                v-for="(day) in schedule" 
                :key="`odd-time-range-${day.dayname}`"
                style="display: none !important;"
            >
                <label class="m-1 p-1">Odd {{day.dayname}}: </label>
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

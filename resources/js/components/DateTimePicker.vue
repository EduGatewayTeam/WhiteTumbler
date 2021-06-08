<script>
import DatePicker from "vue2-datepicker";
import { VBPopover } from "bootstrap-vue";

import "vue2-datepicker/index.css";

export default {
    components: { DatePicker },
    data() {
        return {
            dataTimeRange: null,
            isVisible: false
        };
    },
    directives: {
        "b-popover": VBPopover
    },
    methods: {
        sendData: function() {
            this.$emit("sendDataTimeRange", this.dataTimeRange);
        },
        changeVisibility() {
            this.isVisible = !this.isVisible;
        }
    }
};
</script>

<template>
    <div class="d-flex">
        <date-picker
            v-if="isVisible"
            @input="sendData"
            v-model="dataTimeRange"
            type="datetime"
            range
            placeholder="Select time when room is active(optional)"
        ></date-picker>
        <div
            v-b-popover.hover.top="'Select date and time when room is active'"
            @click="changeVisibility"
            class="m-1"
        >
            <svg
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
    </div>
</template>

<style>
.mx-input {
    font-size: 1rem;
    color: #6c757d;
    height: 2.4rem;
}
.mx-input:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
.dateTimeIcon:hover {
    fill: rgba(30, 58, 138, 0.8);
}
</style>

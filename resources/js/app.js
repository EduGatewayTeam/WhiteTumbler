import 'bootstrap'
import Vue from 'vue'
import VueClipboard from 'vue-clipboard2'
import Toast from "vue-toastification";

// components
import DateTimePicker from './components/DateTimePicker.vue';
import Rooms from './components/Rooms.vue';
import CopyToClipboard from './components/CopyToClipboard.vue';

import '../scss/app.scss'
import "vue-toastification/dist/index.css";

import { VBPopover } from "bootstrap-vue";

import {options} from './toast-options'

Vue.component('copy-link-to-clipboard', CopyToClipboard);

Vue.use(VBPopover);

Vue.component('date-time-picker', DateTimePicker);

Vue.component('w-rooms', Rooms);

VueClipboard.config.autoSetContainer = true;
Vue.use(VueClipboard);

Vue.use(Toast, options);

const app = new Vue({
    el: '#app'
})


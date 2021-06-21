// import default libraries
import Vue from "vue";
import VueClipboard from "vue-clipboard2";
import Toast from "vue-toastification";
import { VBPopover } from "bootstrap-vue";

// import own components
import DateTimePicker from "./components/DateTimePicker.vue";
import Rooms from "./components/Rooms.vue";
import CopyToClipboard from "./components/CopyToClipboard.vue";
import DeleteMeting from "./components/DeleteMeetingButton.vue";
import RoomRecordings from "./components/RoomRecordings.vue";
import ScheduleMeetings from './components/ScheduleMeetings';

// import style files
import "../scss/app.scss";
import "bootstrap";
import "vue-toastification/dist/index.css";

// import config vars 
import { toast_options } from "./config";


// register own components
Vue.component("copy-link-to-clipboard", CopyToClipboard);
Vue.component("delete-meeting", DeleteMeting);
Vue.component("room-recordings", RoomRecordings);
Vue.component("date-time-picker", DateTimePicker);
Vue.component("w-rooms", Rooms);
Vue.component("schedule-meetings", ScheduleMeetings);


VueClipboard.config.autoSetContainer = true;

Vue.use(VueClipboard);
Vue.use(VBPopover);
Vue.use(Toast, toast_options);

const app = new Vue({
    el: "#app"
});

export default app;
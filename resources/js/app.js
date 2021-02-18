import '../scss/app.scss'
import 'bootstrap'
import Vue from 'vue'
import Toast from "vue-toastification";
import axios from 'axios'
import jstz from 'jstz'
import moment from 'moment'
import moment_ from 'moment-timezone'

import "vue-toastification/dist/index.css";

const api = axios.create()
api.defaults.withCredentials = true
api.defaults.headers.common['Content-Type'] = 'application/json'

//toast settings
const options = {
    position: "top-right",
    timeout: 2500,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: true,
    closeButton: "button",
    icon: true,
    rtl: false
};

Vue.component('w-rooms', {
    template: '#rooms-template',
    props: {
        roomsInit: Array
    },
    data() {
        if (!sessionStorage.getItem('timezone')) {
            var tz = jstz.determine() || 'UTC';
            sessionStorage.setItem('timezone', tz.name());
        }
        var currTz = sessionStorage.getItem('timezone');

        return {
            rooms: this.roomsInit,
            roomCreateProcessing: false,
            roomName: '',
            errors: {
                request: '',
                roomName: ''
            },
            activeRoom: null,
            activeRoomIndex: null,
            newMeetingProcessing: false,
            meetingName: '',
            currTz: currTz
        }
    },
    mounted() {
        console.log(this.roomsInit)
    },
    methods: {
        createRoom() {
            this.roomCreateProcessing = true
            api.post('/rooms', {
                name: this.roomName
            }).then((response) => {
                if (response.data.errors) {
                    this.errors = response.data.errors
                } else {
                    this.errors = {}
                    this.roomName = ''
                    this.rooms.push(response.data)
                    $('#addRoomModal').modal('hide')
                }
                console.log(response)
            }).catch((error) => {
                this.errors = {
                    request: error
                }
            }).then(() => {
                this.roomCreateProcessing = false
            })
        },
        openRoom(roomIndex) {
            this.activeRoom = this.rooms[roomIndex]
            this.activeRoomIndex = roomIndex
            $('#roomPage').modal('show')
            console.log('Room name: ' + this.activeRoom.name)
            this.meetings = this.checkMeetingActivation (this.rooms[roomIndex].meetings)
        },
        deleteRoom(roomIndex) {
            api.delete(`/rooms/${this.rooms[roomIndex].id}`)
                .then((response) => {
                    if (response.data.errors) {
                        this.errors = response.data.errors
                    } else {
                        this.errors = {}
                        this.rooms.splice(roomIndex, 1);
                        this.$toast.success(`The room was deleted!`, options);
                    }
                    console.log(response)
                })
                .catch((error) => {
                    this.errors = {
                        request: error
                    }
                })
        },
        addMeeting() {
            this.newMeetingProcessing = true
            api.post('/meetings', {
                roomId: this.activeRoom.id,
                name: this.meetingName
            }).then((response) => {
                if (response.data.errors) {
                    // this.errors = response.data.errors
                } else {
                    this.activeRoom.meetings.push(response.data)
                    this.meetings = this.checkMeetingActivation (this.activeRoom.meetings)
                    Vue.set(this.rooms, this.activeRoomIndex, this.activeRoom)
                    // this.errors = {}
                    this.meetingName = ''
                }
                console.log(response)
            }).catch((error) => {
                this.errors = {
                    request: error
                }
            }).then(() => {
                this.newMeetingProcessing = false
            })
        },
        checkMeetingActivation(meetings){
            meetings.forEach(function(meeting, index){
                let now = new Date(),
                meetingActivateDate = new Date(meeting.activateAt.date)
                if (meeting.deactivateAt !== null) {
                    var meetingDeactivateDate = new Date(meeting.deactivateAt.date)
                    if (now.getTime() >= meetingActivateDate.getTime()){
                        if (now.getTime() < meetingDeactivateDate.getTime()){
                            meeting.isActive = true
                        }
                        else {
                            meeting.isActive = -1
                        }
                    }
                    else {meeting.isActive = false}
                }
                else {
                    meeting.isActive = (now.getTime() >= meetingActivateDate.getTime())
                }
            });
            
            meetings.sort(function(a, b){
                return new Date(a.activateAt.date) - new Date(b.activateAt.date)
            })
            
            return meetings
        },
        dateLocalization(meeting_date, output_format='DD.MM.YY HH:mm') {
            var date = moment(meeting_date).format("YYYY-MM-DDTH:mm");
            var momentTime = moment(date + "Z");
            var tzTime = momentTime.tz(this.currTz);
            var format_date = tzTime.format(output_format);
            return format_date
        }
    }
})

const app = new Vue({
    el: '#app'
})

Vue.use(Toast, options);


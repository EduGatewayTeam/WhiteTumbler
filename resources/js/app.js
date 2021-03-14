import '../scss/app.scss'
import 'bootstrap'
import Vue from 'vue'
import Toast from "vue-toastification";
import axios from 'axios'

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
            console.log(this.activeRoom.name)
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
        }
    }
})

const app = new Vue({
    el: '#app'
})

Vue.use(Toast, options);


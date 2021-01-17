import '../scss/app.scss'
import 'bootstrap'
import { createApp } from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

const api = axios.create()
api.defaults.withCredentials = true
api.defaults.headers.common['Content-Type'] = 'application/json'

const app = createApp({
    data() {
        return {}
    }
}).use(VueAxios, api)

app.component('w-rooms', {
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
            }
        }
    },
    mounted() {
        console.log(this.roomsInit)
    },
    methods: {
        createRoom() {
            this.roomCreateProcessing = true
            this.axios.post('/rooms', {
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
        }
    }
})

app.mount('#app')

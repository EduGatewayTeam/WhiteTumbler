<template>
    <div class="m-4 md:m-12">
        <span class="fs-4 text-gray-700">Записи комнаты</span>
        <div class="row">
            <div class="col-12">
                <table class="table table-image">
                    <thead>
                        <tr>
                            <th class="text-gray-700">Name</th>
                            <th class="text-gray-700">Preview</th>
                            <th class="text-gray-700">Length</th>
                            <th class="text-gray-700">Users</th>
                            <th class="text-gray-700">Visibility</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td
                                colspan="7"
                                class="text-center fs-5 text-gray-500"
                            >
                                You currently have no recordings.
                            </td>
                        </tr>
                        <!--
                        <tr>
                            <td>ДПО дистант 1</th>
                            <td class="w-25">
                                <img
                                    src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/sheep-3.jpg"
                                    class="img-fluid img-thumbnail"
                                    alt="Sheep"
                                    height="50px"
                                />
                            </td>
                            <td>3 h 6 min</td>
                            <td>2</td>
                            <td>Public</td>
                        </tr>
                        <tr>
                            <td>ДПО дистант 3</th>
                            <td class="w-25">
                                <img
                                    src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/sheep-5.jpg"
                                    class="img-fluid img-thumbnail"
                                    alt="Sheep"
                                />
                            </td>
                            <td>2 h 23 min</td>
                            <td>12</td>
                            <td>Public</td>
                        </tr>
                        -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import state from "../state";

let roomRecordings = {
    props: {
        rooms: Array
    },
    mounted() {
        console.log(state);
        console.log("Rooms props:", this.rooms);
    },
    methods: {
        setSelectedRoom() {
            let selectedRoomIndex = state.getState().selectedRoomIndex;
            console.log("New selectedRoomIndex: ", selectedRoomIndex);
        }
    }
};

state.subscribe(roomRecordings.methods.setSelectedRoom);

export default roomRecordings;
</script>

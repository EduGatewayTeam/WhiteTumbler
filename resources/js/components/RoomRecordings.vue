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
                            <th class="text-gray-700">Duration</th>
                            <th class="text-gray-700">Users</th>
                            <th class="text-gray-700">Link</th>
                        </tr>
                    </thead>

                    <tbody id="recordsTable">

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</template>

<script>
import state from "../state";

let roomRecordings = {
    data() {
        return {
            meetingsRecords: []
        };
    },
    props: {
        rooms: Array
    },
    mounted() {
        console.log("meetingsRecords: ", this.meetingsRecords);
    },
    methods: {
        displayRecords() {
            let currentState = state.getState();
            let tbody = document.getElementById("recordsTable");
            let emptyTable = 
                    `<tr>
                        <td
                            colspan="7"
                            @click="showThis"
                            class="text-center fs-5 text-gray-500"
                        >
                            You currently have no recordings.
                        </td>
                    </tr>`;
            if (currentState.activeRoom) {
                //debugger;
                let records = currentState.meetingsRecords[currentState.activeRoom.id] ? currentState.meetingsRecords[currentState.activeRoom.id] : null;
                let table = '';
                records ?
                records.forEach(record => {
                    table += `
                        <tr>
                            <td>${record.name}</td>
                                <td class="w-25">
                                    <img
                                        src="${record.preview}"
                                        class="img-fluid img-thumbnail"
                                        alt="preview"
                                        height="50px"
                                    />
                                </td>
                            <td>${ record.duration }</td>
                            <td>${ record.usersCount }</td>
                            <td><a class="btn btn-sm btn-primary" target="_blank" href="${ record.link }">Просмотреть</a></td>
                        </tr>`;
                })
                :
                null;
                tbody.innerHTML = table ? table : emptyTable;
            }
        },
        showThis() {
            console.log(this);
        },
    }
};

state.subscribe(roomRecordings.methods.displayRecords);

export default roomRecordings;
</script>

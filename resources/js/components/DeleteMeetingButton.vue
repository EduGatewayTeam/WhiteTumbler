<template>
    <span
        @click="deleteMeeting"
        v-b-popover.hover.top="'Delete meeting'"
        class="btn p-2 lh-1 rounded-circle bg-blue-50 bg-blue-400-hover me-1"
    >
        <span
            v-if="meetingDeleteProcessing"
            class="spinner-border spinner-size-24"
            role="status"
        >
            <span class="visually-hidden">Loading...</span>
        </span>

        <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            fill="currentColor"
            class="bi bi-x-circle"
            viewBox="0 0 16 16"
        >
            <path
                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"
            />
            <path
                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"
            />
        </svg>
    </span>
</template>

<script>
import { VBPopover } from "bootstrap-vue";
import api from "../api";

export default {
    props: {
        meetingId: String
    },
    directives: {
        "b-popover": VBPopover
    },
    data() {
        return {
            meetingDeleteProcessing: false
        };
    },
    methods: {
        deleteMeeting() {
            if (this.meetingDeleteProcessing) {
                return;
            }

            this.meetingDeleteProcessing = true;
            console.log("meetingId: ", this.meetingId);
            api.delete(`/meetings/${this.meetingId}`)
                .then(response => {
                    if (response.data.errors) {
                        this.$toast.error(
                            `The meeting was not deleted: ${response.data.errors}`
                        );
                    } else {
                        document.getElementById(`${this.meetingId}`).remove();
                        this.$toast.success(`The meeting was deleted.`);
                    }
                })
                .catch(error => {
                    this.$toast.error(`The meeting was not deleted: ${error}`);
                })
                .finally(() => {
                    this.meetingDeleteProcessing = false;
                });
        }
    }
};
</script>

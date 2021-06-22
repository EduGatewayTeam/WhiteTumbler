<template>
    <button
        v-clipboard:copy="getFullLink()"
        v-clipboard:success="onCopy"
        v-clipboard:error="onError"
        class="congig-btn btn ml-3 p-1 rounded-circle lh-1"
    >
        <i class="fa fa-copy"></i>
        <i class="fa fa-check" style="display: none;"></i>
    </button>
</template>

<script>
import { VBPopover } from "bootstrap-vue"; // импортируем библиотеку для отображения всплывающих окон

export default {
    props: {
        meetingLink: String
    },
    directives: {
        "b-popover": VBPopover
    },
    methods: {
        getFullLink() {
            return window.location.host + this.meetingLink;
        },
        onCopy: function(e) {
            this.$el.childNodes[0].style.display = "none";
            this.$el.childNodes[2].style.display = "block";

            setTimeout(() => {
                this.$el.childNodes[0].style.display = "block";
                this.$el.childNodes[2].style.display = "none";
            }, 1500);
        },
        onError: function(e) {
            alert("Failed to copy link");
        }
    }
};
</script>

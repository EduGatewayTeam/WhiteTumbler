<template>
    <span
        v-clipboard:copy="meetingLink"
        v-clipboard:success="onCopy"
        v-clipboard:error="onError"
        class="btn p-2 lh-1 rounded-circle bg-blue-50 bg-blue-400-hover me-1"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            fill="currentColor"
            class="bi bi-files"
            viewBox="0 0 16 16"
        >
            <path
                d="M13 0H6a2 2 0 0 0-2 2 2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 13V4a2 2 0 0 0-2-2H5a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1zM3 4a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z"
            />
        </svg>

        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            fill="currentColor"
            class="bi bi-check"
            viewBox="0 0 16 16"
            style="display: none"
        >
            <path
                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"
            />
        </svg>
    </span>
</template>

<script>
export default {
    props: {
        meetingLink: String
    },
    methods: {
        onCopy: function(e) {
            console.log(this)

            this.$el.childNodes[0].style.display = "none"
            this.$el.childNodes[2].style.display = "block"
            
            setTimeout( () => {
                this.$el.childNodes[0].style.display = "block"
                this.$el.childNodes[2].style.display = "none"
            }, 2000)

        },
        onError: function(e) {
            alert("Failed to copy link");
        }
    }
};
</script>

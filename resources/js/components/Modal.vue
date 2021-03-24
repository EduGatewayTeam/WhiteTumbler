<template>
    <div class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <slot name="header"/>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <slot name="body"/>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"></button>
                    <slot name="footer"/>
                </div>

            </div>
        </div>
    </div>
</template>

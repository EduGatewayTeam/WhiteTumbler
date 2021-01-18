@extends('layout')

@push('components')
    <script type="text/x-template" id="rooms-template">
        <div>
        <div class="modal fade" id="addRoomModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">{{ __('rooms.create-room-title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form v-on:submit.prevent="createRoom" class="p-4">
                            <div class="row alert alert-danger" role="alert" v-show="errors.request">
                                Request error!
                            </div>
                            <div class="row">
                                <label for="inputRoomCreateName" class="form-label">{{ __('rooms.room-title-label') }}</label>
                                <input :disabled="roomCreateProcessing" v-model="roomName" :class="{ 'is-invalid': errors.name }"
                                    type="text" class="form-control" id="inputRoomCreateName">
                                <div class="invalid-feedback">
                                    <span v-for="error in errors.name">@{{ error }}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('rooms.cancel') }}</button>
                        <button :disabled="roomCreateProcessing" @click="createRoom"
                            type="button" class="d-flex align-items-center btn text-white bg-blue-500 bg-blue-600-hover">
                            <span v-show="roomCreateProcessing"
                                class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                            <span class="ms-2">{{ __('rooms.room-create') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="roomPage" tabindex="-1" aria-labelledby="roomPageLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div v-if="activeRoom !== null" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="roomPageLabel">@{{ activeRoom.name }}</h5>
                        <button type="button" class="btn-close text-gray-50" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div v-for="(meeting, index) in activeRoom.meetings">
                            <span>@{{ meeting.name }}</span>
                        </div>
                        <div v-if="activeRoom.meetings.length === 0">
                            <span class="fs-4 text-gray-400">
                                {{ __('rooms.no-meetings') }}
                            </span>
                        </div>

                        <div class="mt-2 d-flex align-items-center">
                            <div>
                                <input :disabled="newMeetingProcessing" v-model="meetingName"
                                       :class="{ 'is-invalid': errors.name }"
                                       type="text" class="form-control" id="inputRoomCreateName"
                                       placeholder="{{ __('rooms.meeting-name') }}">
                            </div>
                            <div>
                                <button @click="addMeeting"
                                        type="button"
                                        class="btn p-2 ms-2 border-0 bg-blue-500 bg-blue-600-hover text-white rounded-circle lh-1">
                                    <span v-if="newMeetingProcessing"
                                          class="spinner-border spinner-border-24" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="m-4 md:m-12">
            <div class="d-flex align-items-center justify-content-between">
                    <span class="fs-3 text-gray-700">
                        Rooms
                    </span>
                <button type="button" class="btn p-2 border-0 bg-blue-500 bg-blue-600-hover text-white rounded-circle lh-1"
                        data-bs-toggle="modal" data-bs-target="#addRoomModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </button>
            </div>
            <div class="row">
                <div v-for="(room, index) in rooms" class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="bg-white rounded-3">
                        <div class="d-flex align-items-center bg-blue-900 p-2 rounded-top-3">
                            <a @click="openRoom(index)"
                                href="#" class="p-0 fs-5 mb-0 ms-2 text-white text-wrap text-break text-decoration-underline text-start">
                                @{{ room.name }}
                            </a>
                        </div>
                        <div class="px-3 py-4">
                            <p class="py-2 mb-0">Последняя сессия:</p><p class="mb-0">Ноябрь 28, 2020</p>
                            <div class="d-flex align-items-center mt-4 text-gray-700">
                                <svg height="32" width="32" class="w-6 h-6 fill-current" viewBox="0 0 512 512">
                                    <path d="M239.208 343.937c-17.78 10.103-38.342 15.876-60.255 15.876-21.909 0-42.467-5.771-60.246-15.87C71.544 358.331 42.643 406 32 448h293.912c-10.639-42-39.537-89.683-86.704-104.063zM178.953 120.035c-58.479 0-105.886 47.394-105.886 105.858 0 58.464 47.407 105.857 105.886 105.857s105.886-47.394 105.886-105.857c0-58.464-47.408-105.858-105.886-105.858zm0 186.488c-33.671 0-62.445-22.513-73.997-50.523H252.95c-11.554 28.011-40.326 50.523-73.997 50.523z" />
                                    <g>
                                        <path d="M322.602 384H480c-10.638-42-39.537-81.691-86.703-96.072-17.781 10.104-38.343 15.873-60.256 15.873-14.823 0-29.024-2.654-42.168-7.49-7.445 12.47-16.927 25.592-27.974 34.906C289.245 341.354 309.146 364 322.602 384zM306.545 200h100.493c-11.554 28-40.327 50.293-73.997 50.293-8.875 0-17.404-1.692-25.375-4.51a128.411 128.411 0 0 1-6.52 25.118c10.066 3.174 20.779 4.862 31.895 4.862 58.479 0 105.886-47.41 105.886-105.872 0-58.465-47.407-105.866-105.886-105.866-37.49 0-70.427 19.703-89.243 49.09C275.607 131.383 298.961 163 306.545 200z" />
                                    </g>
                                </svg>
                                <h1 class="px-2 fs-6 mb-0">Владелец</h1>
                                <button class="btn p-1 rounded-circle lh-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="rooms.length === 0" class="my-5 text-center">
                    <span class="fs-3 text-gray-400">
                        {{ __('rooms.empty') }}
                    </span>
                </div>
            </div>
        </div>
        </div>
    </script>
@endpush

@push('components')
    <script type="text/x-template" id="room-page">

    </script>
@endpush

@section('content')
    <div class="container w-full pt-10 mx-auto">
        <div class="w-full px-4 mb-16 leading-normal text-gray-800 md:px-0 md:mt-8">
            <w-rooms :rooms-init='@json($rooms)'></w-rooms>
        </div>
    </div>
@endsection

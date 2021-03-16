@extends('layout')

@push('components')
    <script type="text/x-template" id="rooms-template">

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('rooms.delete-room') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    <div class="modal-body">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                {{ __('rooms.confirm-deleting') }}
                            </h3>
                        </div>
                    </div>
                        
                    <div class="modal-footer">   
                        <button type="button" class="btn btn-secondary">{{ __('rooms.cancel') }}</button>
                        <button :disabled="roomDeleteProcessing" @click="deleteRoomConfirm" type="button" class="d-flex align-items-center btn text-white bg-blue-500 bg-blue-600-hover">
                            <span v-show="roomDeleteProcessing"
                                class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                            <span class="ms-2">{{ __('rooms.delete-room') }}</span>
                        </button>
                    </div>
                    
                </div>

            </div>
        </div>

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
                        <h5 class="modal-title" id="roomPageLabel">@{{ activeRoom . name }}</h5>
                        <button type="button" class="btn-close text-gray-50" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div v-for="(meeting, index) in meetings">
                            <div v-if="meeting.isActive >= 0" class="p-2 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div
                                        v-if="meeting.isActive > 0"
                                        class="spinner-grow spinner-grow-sm text-danger" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span class="ms-3">@{{ meeting . name }}</span>
                                    <span class="ms-3">@{{ dateLocalization(meeting . activateAt['date']) }}</span>
                                </div>

                                <div >
                                    <a :href="`/meetings/${meeting.id}/join`" class="btn p-2 lh-1 rounded-circle bg-blue-50 bg-blue-400-hover">
                                        <svg v-if="meeting.isActive == 0"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                            <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                                            <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                                            <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/> -->
                                        </svg>
                                        <svg v-else-if="meeting.isActive > 0"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                                        </svg>
                                    </a>
                                </div>

                            </div>
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
                                                class="spinner-border spinner-size-24" role="status">
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
                                    href="#" class="no-underline p-0 fs-5 mb-0 ms-2 text-white text-wrap text-break text-start">
                                    @{{ room . name }}
                                </a>
                            </div>
                            <div class="px-3 py-4 flex">
                                <p class="py-2 mb-0">Последняя сессия:</p><p class="mb-0">Ноябрь 28, 2020</p>
                                <hr>
                                <div class="d-flex align-items-center mt-4 text-gray-700">
                                    <svg height="32" width="32" class="w-6 h-6 fill-current" viewBox="0 0 512 512">
                                        <path d="M239.208 343.937c-17.78 10.103-38.342 15.876-60.255 15.876-21.909 0-42.467-5.771-60.246-15.87C71.544 358.331 42.643 406 32 448h293.912c-10.639-42-39.537-89.683-86.704-104.063zM178.953 120.035c-58.479 0-105.886 47.394-105.886 105.858 0 58.464 47.407 105.857 105.886 105.857s105.886-47.394 105.886-105.857c0-58.464-47.408-105.858-105.886-105.858zm0 186.488c-33.671 0-62.445-22.513-73.997-50.523H252.95c-11.554 28.011-40.326 50.523-73.997 50.523z" />
                                        <g>
                                            <path d="M322.602 384H480c-10.638-42-39.537-81.691-86.703-96.072-17.781 10.104-38.343 15.873-60.256 15.873-14.823 0-29.024-2.654-42.168-7.49-7.445 12.47-16.927 25.592-27.974 34.906C289.245 341.354 309.146 364 322.602 384zM306.545 200h100.493c-11.554 28-40.327 50.293-73.997 50.293-8.875 0-17.404-1.692-25.375-4.51a128.411 128.411 0 0 1-6.52 25.118c10.066 3.174 20.779 4.862 31.895 4.862 58.479 0 105.886-47.41 105.886-105.872 0-58.465-47.407-105.866-105.886-105.866-37.49 0-70.427 19.703-89.243 49.09C275.607 131.383 298.961 163 306.545 200z" />
                                        </g>
                                    </svg>
                                    <h2 class="px-2 fs-6 mb-0">{{ Auth::user()->getName() }}</h2>

                                    <button class="btn ml-3 p-1 rounded-circle lh-1">
                                        <i class="fa fa-cog"></i>
                                    </button>
                                    <button @click="deleteRoom(index)" class="btn p-1 rounded-circle lh-1">
                                        <i class="fa fa-trash"></i>
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

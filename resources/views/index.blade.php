@extends('layout')

@section('title')
Rooms
@endsection

@push('components')
    <script type="text/x-template" id="rooms-template">
        <div>

            @include('modals.confirm-delete-modal')

            @include('modals.set-moderators-room-modal')

            @include('modals.room-settings-modal')

            @include('modals.add-room-modal')

            @include('modals.manage-room-modal')

            <div class="m-4 md:m-12">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="fs-3 text-gray-700">
                        Rooms
                    </span>
                    <button type="button" class="btn p-2 border-0 bg-blue-500 bg-blue-600-hover text-white rounded-circle lh-1"
                            data-bs-toggle="modal" data-bs-target="#add-room-modal"
                            v-b-popover.hover.left="'Create room'"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </button>
                </div>

                <div class="row">
                    <div v-for="(room, index) in rooms" class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">

                        <div class="bg-white rounded-3" @click="setSelectedRoomIndex(index)">

                            <div @click="openRoom(index)" class="d-flex align-items-center bg-blue-800 p-2 rounded-top-3">
                                <i class="fas fa-chalkboard-teacher text-white"> </i>
                                <a
                                    href="#" class="mx-3 text-white font-semibold text-white room-name-link">
                                    @{{ room . name }}
                                </a>

                            </div>

                            <div class="px-3 py-4 flex">
                                <p class="py-2 mb-0">Последняя сессия:</p><p class="mb-0">Ноябрь 28, 2020</p>
                                <hr>
                                <div class="d-flex align-items-center mt-4 text-gray-700">
                                    <h2 class="fs-6 mb-0">{{ Auth::user()->getName() }}</h2>

                                    <copy-link-to-clipboard :meetingLink='`/room/${room.id}/join`'></copy-link-to-clipboard>

                                    <button @click="openModersModal(index)" class="congig-btn btn ml-3 p-1 rounded-circle lh-1">
                                        <i class="fa fa-user-plus"></i>
                                    </button>

                                    <button @click="openRoomSettings(index)" class="congig-btn btn ml-3 p-1 rounded-circle lh-1">
                                        <i class="fa fa-cog"></i>
                                    </button>

                                    <button @click="deleteRoom(index)" class="delete-btn btn p-1 rounded-circle lh-1">
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

            <hr class="border-b-2 border-gray-400 my-8 mx-4">

            <room-recordings :rooms='rooms'></room-recordings>

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

@extends('layout')

@push('components')
    <script type="text/x-template" id="rooms-template">
        <div>

            @include('modals.confirm-delete-modal')

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
                            
                            <div class="d-flex align-items-center bg-blue-800 p-2 rounded-top-3">
                                <i class="fas fa-chalkboard-teacher text-white"> </i>
                                <a @click="openRoom(index)"
                                    href="#" class="mx-3 text-white font-semibold text-white room-name-link">
                                    @{{ room . name }}
                                </a>

                            </div>

                            <div class="px-3 py-4 flex">
                                <p class="py-2 mb-0">Последняя сессия:</p><p class="mb-0">Ноябрь 28, 2020</p>
                                <hr>
                                <div class="d-flex align-items-center mt-4 text-gray-700">
                                    <svg height="24" width="24" class="w-6 h-6 fill-current" viewBox="0 0 512 512">
                                        <path d="M239.208 343.937c-17.78 10.103-38.342 15.876-60.255 15.876-21.909 0-42.467-5.771-60.246-15.87C71.544 358.331 42.643 406 32 448h293.912c-10.639-42-39.537-89.683-86.704-104.063zM178.953 120.035c-58.479 0-105.886 47.394-105.886 105.858 0 58.464 47.407 105.857 105.886 105.857s105.886-47.394 105.886-105.857c0-58.464-47.408-105.858-105.886-105.858zm0 186.488c-33.671 0-62.445-22.513-73.997-50.523H252.95c-11.554 28.011-40.326 50.523-73.997 50.523z" />
                                        <g>
                                            <path d="M322.602 384H480c-10.638-42-39.537-81.691-86.703-96.072-17.781 10.104-38.343 15.873-60.256 15.873-14.823 0-29.024-2.654-42.168-7.49-7.445 12.47-16.927 25.592-27.974 34.906C289.245 341.354 309.146 364 322.602 384zM306.545 200h100.493c-11.554 28-40.327 50.293-73.997 50.293-8.875 0-17.404-1.692-25.375-4.51a128.411 128.411 0 0 1-6.52 25.118c10.066 3.174 20.779 4.862 31.895 4.862 58.479 0 105.886-47.41 105.886-105.872 0-58.465-47.407-105.866-105.886-105.866-37.49 0-70.427 19.703-89.243 49.09C275.607 131.383 298.961 163 306.545 200z" />
                                        </g>
                                    </svg>
                                    <h2 class="px-2 fs-6 mb-0">{{ Auth::user()->getName() }}</h2>

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

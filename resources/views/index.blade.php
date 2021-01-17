@extends('layout')

@push('components')
    <script type="text/x-template" id="rooms-template">
        <div class="modal fade" id="addRoomModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
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
                <div v-for="room in rooms" class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3">
                    <div class="bg-white rounded-3">
                        <div class="d-flex align-items-center bg-blue-900 p-2 rounded-top-3">
                            <i class="text-white fas fa-chalkboard-teacher"></i>
                            <h1 class="fs-5 mb-0 ms-2 text-white">@{{ room.name }}</h1>
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
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="rooms.length === 0" class="my-5 text-center">
                    <span class="fs-3 text-gray-400">
                        {{ __('rooms.empty') }}
                    </span>
                </div>
            </div>
        </div>
    </script>
@endpush

@section('content')


    <div class="container w-full pt-10 mx-auto">

        <div class="w-full px-4 mb-16 leading-normal text-gray-800 md:px-0 md:mt-8">

            <w-rooms :rooms-init='@json(\Illuminate\Support\Facades\Auth::user()->getRooms()->toArray())'></w-rooms>

        </div>


    </div>
@endsection

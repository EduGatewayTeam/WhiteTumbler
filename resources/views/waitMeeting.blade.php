@extends('layout')

@section('title')
Room: {{ $roomName }}
@endsection

@section('content')
<div class="container w-full pt-10 mx-auto">
    <div class="w-full px-4 mb-16 leading-normal text-gray-800 md:px-0 md:mt-8">
        <div class="m-4 md:m-12">
            <div class="d-flex align-items-center justify-content-between">
                <span class="fs-3 text-gray-700">
                    Room: {{ $roomName }}
                </span>
            </div>

            <div class="row">
                <div class="my-5 text-center">
                    <span class="fs-3 text-gray-600">
                        {{ __('rooms.dont-start-meeting') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    window.setInterval('refresh()', 5000);

    // Refresh or reload page.
    function refresh() {
        window .location.reload();
    }
</script>

@endpush

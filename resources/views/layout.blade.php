@extends('base')

@section('page')
    <nav class="navbar navbar-expand-lg navbar-light bg-white p-3 rounded-bottom-5 shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center justify-content-start m-0" href="/">
                <img width="32" height="32" src="{{ asset('favicon.ico') }}" alt="">
                <span class="ms-2 fs-5">{{ config('app.name') }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="pt-3 pt-md-0 collapse navbar-collapse" id="navbarToggler">
                <div class="d-flex align-items-center ms-auto mt-1">
                    
                    <a class="link px-3 header-nav text-gray-500" href="/"> 
                        <i class="fas fa-home pr-1"></i><span class="d-none d-sm-inline-block m-1">Home</span>
                    </a>

                    <a class="link px-3 header-nav text-gray-500" href="/recordings">
                        <i class="fas fa-video pr-1"></i><span class="d-none d-sm-inline-block m-1">All Recordings</span>
                    </a>

                    <span class="text-gray-600 fw-bolder me-3">
                        {{ Auth::user()->getAbbreviatedFullName() }}
                    </span>
                    <a href="{{ route('logout') }}" class="btn text-gray-50 bg-blue-500 bg-blue-600-hover ms-auto m-1">
                        {{ __('auth.logout') }}
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
@yield('content')
@endsection


@extends('base')

@section('page')
<div class="vh-100 d-flex row">
    <div class="d-block col-md-6 col-sm-8 row p-4 m-auto rounded-3 bg-white shadow-lg">
        <div class="text-center fs-4 col">{{ __('auth.not-logged') }}</div>
        <div class="text-center fs-4 col">{{ __('auth.login-as') }}</div>

        <a href="{{ route('external_auth', ["provider" => "student-provider"]) }}" class="btn btn-primary mt-3 col col-md-6 offset-md-3 col-sm-8 offset-sm-2">
            {{ __('auth.student-login') }}
        </a>
        <a href="{{ route('external_auth', ["provider" => "lecturer-provider"]) }}" class="btn btn-primary mt-3 col col-md-6 offset-md-3 col-sm-8 offset-sm-2">
            {{ __('auth.lecturer-login') }}
        </a>
    </div>
</div>
@endsection

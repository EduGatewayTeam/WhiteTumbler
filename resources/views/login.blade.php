
@extends('base')

@section('page')
<div class="container w-full mx-auto pt-20">
    <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">
        <div class="flex flex-row ">
            <div class="w-full flex flex-col items-center justify-center bg-gray-100">
                <div class="flex flex-col bg-white shadow-md px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-lg w-full max-w-md">
                    <div class="font-medium self-center text-l sm:text-xl text-gray-800">{{ __('auth.not-logged') }}</div>
                    <div class="font-medium self-center text-l sm:text-xl text-gray-800">{{ __('auth.login-as') }}</div>

                    <div class="flex w-full mt-4">
                        <a href="{{ route('external_auth', ["provider" => "student-provider"]) }}"
                           class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-blue-500 hover:bg-blue-600 rounded-md py-2 w-full transition duration-150 ease-in">
                            <span class="mr-2">{{ __('auth.student-login') }}</span>
                        </a>
                    </div>

                    <div class="flex w-full mt-4">
                        <a href="{{ route('external_auth', ["provider" => "lecturer-provider"]) }}"
                           class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-blue-500 hover:bg-blue-600 rounded-md py-2 w-full transition duration-150 ease-in">
                            <span class="mr-2">{{ __('auth.lecturer-login') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

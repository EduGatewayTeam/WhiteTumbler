<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'student-provider' => [
        'base_url' => env('STUDENT_KEYCLOAK_BASE_URL'),
        'realms' => env('STUDENT_KEYCLOAK_REALM'),
        'client_id' => env('STUDENT_KEYCLOAK_CLIENT_ID'),
        'client_secret' => env('STUDENT_KEYCLOAK_CLIENT_SECRET'),
        'redirect' => env('STUDENT_KEYCLOAK_REDIRECT_URI'),
    ],
    'lecturer-provider' => [
        'base_url' => env('LECTURER_KEYCLOAK_BASE_URL'),
        'realms' => env('LECTURER_KEYCLOAK_REALM'),
        'client_id' => env('LECTURER_KEYCLOAK_CLIENT_ID'),
        'client_secret' => env('LECTURER_KEYCLOAK_CLIENT_SECRET'),
        'redirect' => env('LECTURER_KEYCLOAK_REDIRECT_URI'),
    ],

];

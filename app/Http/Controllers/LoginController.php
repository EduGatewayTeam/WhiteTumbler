<?php


namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     */
    public function redirectToProvider()
    {
        return Socialite::driver('keycloak')
            ->setScopes(['email'])  // keycloak needs scopes to be not empty
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('keycloak')->user();

        // $user->token;
        return new JsonResponse($user);
    }
}

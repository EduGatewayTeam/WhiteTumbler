<?php


namespace App\Http\Controllers;


use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    /**
     * @Get("/login/{provider}", middleware="web")
     * @param $provider
     * @return mixed
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)
            ->setScopes(['email'])  // keycloak needs scopes to be not empty
            ->redirect();
    }

    /**
     * @Get("/login/{provider}/callback", middleware="web")
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        // $user->token;
        return new JsonResponse($user);
    }
}

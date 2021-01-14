<?php


namespace App\Http\Controllers;


use App\User;
use Collective\Annotations\Routing\Annotations\Annotations\Any;
use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    /**
     * @Get("/login/{provider}", middleware="web", as="login")
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
     * @param EntityManagerInterface $em
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider, EntityManagerInterface $em)
    {
        $socialiteUser = Socialite::driver($provider)->user();
        $uuid = $socialiteUser->getId();

        $repository = $em->getRepository(User::class);
        $user = $repository->find($uuid);
        if ($user == null) {
            $user = new User($uuid);
            $em->persist($user);
            $em->flush();
        }

        Auth::login($user);

        return redirect()->intended(route('index'));
    }

    /**
     * @Any("/logout", middleware="web", as="logout")
     */
    public function logout() {
        Auth::logout();
        return redirect(route('index'));
    }
}

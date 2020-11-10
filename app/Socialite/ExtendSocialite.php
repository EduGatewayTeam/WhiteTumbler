<?php


namespace App\Socialite;



use SocialiteProviders\Manager\SocialiteWasCalled;

class ExtendSocialite
{

    public const STUDENT_PROVIDER = StudentSocialiteProvider::IDENTIFIER;
    public const LECTURER_PROVIDER = LecturerSocialiteProvider::IDENTIFIER;

    /**
     * Register custom providers.
     * @param SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(self::STUDENT_PROVIDER, StudentSocialiteProvider::class);
        $socialiteWasCalled->extendSocialite(self::LECTURER_PROVIDER, LecturerSocialiteProvider::class);
    }

}

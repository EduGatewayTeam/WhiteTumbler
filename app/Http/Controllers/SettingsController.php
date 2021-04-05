<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{

    public static function addSettings(array $request, EntityManagerInterface $em)
    {
        $settings = new Settings(
            isset($request['mute_on_startup']) ? $request['mute_on_startup'] : true,
            isset($request['expect_moderator']) ? $request['expect_moderator'] : true
        );

        $em->persist($settings);
        $em->flush();

        return $settings;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @Put("/settings", middleware="web")
     * @Middleware("auth")
     */
    public function updateSettings(Request $request, EntityManagerInterface $em)
    {
        $user = Auth::user();

        $repository = $em->getRepository(Settings::class);
        $settings = $repository->find($request->settingsId);

        if (isset($request->expect_moderator)) {
            $settings->set_expect_moderator($request->expect_moderator);
        }
        if (isset($request->mute_on_startup)) {
            $settings->set_mute_on_startup($request->mute_on_startup);
        }

        $em->persist($settings);
        $em->flush();

        return new JsonResponse($settings);
    }

    public static function deleteSettings($settingsId, EntityManagerInterface $em)
    {
        $repository_settings = $em->getRepository(Settings::class);
        $settings = $repository_settings->find($settingsId);

        $em->remove($settings);
        $em->flush();
    }
}

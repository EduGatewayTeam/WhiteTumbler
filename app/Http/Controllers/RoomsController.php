<?php

namespace App\Http\Controllers;

use App\Room;
use App\User;
use App\Moderator;
use App\Settings;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Collective\Annotations\Routing\Annotations\Annotations\Post;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ModeratorsController;
use App\Http\Controllers\SettingsController;
use Highlight\Mode;

class RoomsController extends Controller
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @Post("/rooms", middleware="web")
     * @Middleware("auth")
     */
    public function createRoom(Request $request, EntityManagerInterface $em) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1|max:255'
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'errors' => $validator->errors()
            ]);
        }

        $default_settings = SettingsController::addSettings($request->default_meeting_settings, $em);

        $room = new Room();
        $room->setName($request->name);
        $room->setCreator(Auth::user());
        $room->setDefaultMeetingSettings($default_settings);
        $em->persist($room);
        $em->flush();

        ModeratorsController::setModerator($room, Auth::user(), $em);

        return new JsonResponse($room);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @Put("/rooms", middleware="web")
     * @Middleware("auth")
     */
    public function updateRoom(Request $request, EntityManagerInterface $em) {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1|max:255'
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'errors' => $validator->errors()
            ]);
        }

        $repository = $em->getRepository(Room::class);
        $room = $repository->find($request->roomId);

        $room->setName($request->name);
        
        $em->persist($room);
        $em->flush();

        return new JsonResponse($room);
    }


    /**
     * @param $roomId
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @DELETE("/rooms/{roomId}", middleware="web")
     * @Middleware("auth")
     */
    public function deleteRoom($roomId, EntityManagerInterface $em) {
        $user = Auth::user();

        $repository_rooms = $em->getRepository(Room::class);
        $room = $repository_rooms->find($roomId);

        SettingsController::deleteSettings($room->default_meeting_settings, $em);

        $em->remove($room);
        $em->flush();

        return new JsonResponse($room);
    }

}

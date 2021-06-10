<?php

namespace App\Http\Controllers;

use App\Room;
use App\Schedule;
use App\User;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Collective\Annotations\Routing\Annotations\Annotations\Post;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $room = new Room();
        $room->setName($request->name);
        $room->setCreator(Auth::user());
        $em->persist($room);
        $em->flush();

        return new JsonResponse($room);
    }


    /**
     * @param $roomId
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @Patch("/room/{roomId}", middleware="web")
     * @Middleware("auth")
     */
    public function updateRoom($roomId, Request $request, EntityManagerInterface $em) {
        $repository = $em->getRepository(Room::class);
        $room = $repository->find($roomId);

        $room->clearSchedules();
        $em->flush();

        foreach ($request->get('schedule') as $schedule) {
            $room->addSchedule(new Schedule(
                $schedule['day_type'],
                $schedule['week_day'],
                $schedule['time_start'],
                $schedule['time_end']));
        }
        $em->flush();

        return new JsonResponse(['result' => 'ok']);
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

        $repository = $em->getRepository(Room::class);
        $room = $repository->find($roomId);

        $em->remove($room);
        $em->flush();

        return new JsonResponse($room);
    }

}

<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Room;
use App\User;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Collective\Annotations\Routing\Annotations\Annotations\Post;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MeetingsController extends Controller
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @Post("/meetings", middleware="web")
     * @Middleware("auth")
     */
    public function newMeeting(Request $request, EntityManagerInterface $em) {
        $validator = Validator::make($request->all(), [
            'roomId' => 'required|exists:App\Room,id',
            'name' => 'required|min:1|max:255'
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'errors' => $validator->errors()
            ]);
        }

        $repository = $em->getRepository(Room::class);
        $room = $repository->find($request->roomId);

        $meeting = new Meeting();
        $meeting->setRoom($room);
        $meeting->setName($request->name);
        $em->persist($meeting);
        $em->flush();

        return new JsonResponse($meeting);
    }

}

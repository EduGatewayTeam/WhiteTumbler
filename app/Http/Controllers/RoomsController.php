<?php

namespace App\Http\Controllers;

use App\Room;
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

}

<?php


namespace App\Http\Controllers;


use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{

    /**
     * @Get("/", middleware="web", as="index")
     * @Middleware("auth")
     * @return mixed
     */
    public function redirectToProvider()
    {
        $rooms = array_map(function ($room) {
            return $room->jsonSerialize();
        }, Auth::user()->getRooms()->toArray());
        return view('index', ['rooms' => $rooms]);
    }

}

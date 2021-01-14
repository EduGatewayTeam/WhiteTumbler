<?php


namespace App\Http\Controllers;


use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;

class MainController extends Controller
{

    /**
     * @Get("/", middleware="web", as="index")
     * @Middleware("auth")
     * @return mixed
     */
    public function redirectToProvider()
    {
        return view('index');
    }

}

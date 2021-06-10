<?php


namespace App\Http\Controllers;

use App\Util\FilteredObjectsArray;
use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        // $rooms = array_map(function ($room) {
        //     return [
        //         'id' => $room->id,
        //         'name' => $room->name,
        //         'schedule' => $room->getSchedules()->toArray()
        //     ];
        // }, );
        return view('index', ['rooms' => Auth::user()->getRooms()->toArray()]);
    }


    /**
     * @Post("/search", middleware="web", as="user_search")
     * @Middleware("auth")
     * @return mixed
     */
    public function searchUser(Request $request, EntityManagerInterface $em) {
        $q = $request->get('query');
        $query = $em->createQuery('SELECT u FROM App\User u WHERE tsplainquery(u.tsvector,:query) = true');
        $query->setParameter('query', $q);
        $result = $query->getArrayResult();
        return new JsonResponse((new FilteredObjectsArray($result))->with(['id', 'name', 'surname', 'patronymic'])->get());
    }

}

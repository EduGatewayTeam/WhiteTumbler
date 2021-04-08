<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Room;
use App\User;
use App\Moderator;
use PhpParser\Node\Expr\AssignOp\Mod;

class ModeratorsController extends Controller
{
    public static function setModerator(Room $room, User $user, EntityManagerInterface $em) {
        $moderator = new Moderator();
        $moderator->setRoom($room);
        $moderator->setUser($user);
        $em->persist($moderator);
        $em->flush();

        return $moderator->jsonSerialize();
    }
}

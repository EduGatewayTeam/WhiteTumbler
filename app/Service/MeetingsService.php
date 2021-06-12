<?php

namespace App\Service;

use App\Meeting;
use App\Room;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Str;

class MeetingsService {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }


    public function createMeeting(Room $room) {
        $meeting = new Meeting();
        $meeting->setModeratorPassword(Str::random(32));
        $meeting->setAttendeePassword(Str::random(32));
        $meeting->setName($room->getName());
        $meeting->setRoom($room);
        $meeting->setStartTime(new DateTime());
        $meeting->setDuration(0);

        $this->em->persist($meeting);
        $this->em->flush();

        return $meeting;
    }




}


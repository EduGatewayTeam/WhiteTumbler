<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Room;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Collective\Annotations\Routing\Annotations\Annotations\Get;
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


    /**
     * @param $meetingId
     * @param EntityManagerInterface $em
     * @return mixed
     * @Get("/meetings/{meetingId}/join", middleware="web", as="join_meeting")
     * @Middleware("auth")
     */
    public function join($meetingId, EntityManagerInterface $em) {
        $repository = $em->getRepository(Meeting::class);
        $meeting = $repository->find($meetingId);

        $user = Auth::user();

        $moderator = $meeting->getRoom()->getCreator()->getId() == $user->getId();

        $bbb                 = new BigBlueButton();
        $createMeetingParams = new CreateMeetingParameters($meeting->id, $meeting->name);
        $createMeetingParams->setModeratorPassword('moderator_password');
        $createMeetingParams->setAttendeePassword('attendee_password');
        $response = $bbb->createMeeting($createMeetingParams);

        $joinMeetingParams = new JoinMeetingParameters($meetingId, Auth::user()->getName(),
            $moderator ? $response->getModeratorPassword() : $response->getAttendeePassword());
        $joinMeetingParams->setRedirect(true);
        $url = $bbb->getJoinMeetingURL($joinMeetingParams);

        return redirect($url);
    }

}

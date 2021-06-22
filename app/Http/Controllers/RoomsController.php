<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Room;
use App\Schedule;
use App\Service\MeetingsService;
use App\User;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Collective\Annotations\Routing\Annotations\Annotations\Post;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoomsController extends Controller
{

    private $meetingsService;

    public function __construct(EntityManagerInterface $em) {
        $this->meetingsService = new MeetingsService($em);
    }

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
     * @Get("/rooms", middleware="web")
     * @Middleware("auth")
     */
    public function getRooms() {
        return new JsonResponse(['rooms' => Auth::user()->getRooms()->toArray()]);
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
     * @Get("/room/{roomId}/join", middleware="web")
     * @Middleware("auth")
     */
    public function joinRoom($roomId, EntityManagerInterface $em) {
        $repository = $em->getRepository(Room::class);

        try {
            /** @var Room */
            $room = $repository->find($roomId);
        } catch (Exception $e) {
            abort(404);
        }


        if ($room == null) {
            abort(404);
        }

        /** @var User */
        $user = Auth::user();

        if ($room->getMeeting() == null) {
            if ($room->getCreator()->getId() == $user->getId()) {
                // manual meeting, immediatly create in bbb
                $this->meetingsService->createMeeting($room);
            } else {
                return view('waitMeeting', ['roomName' => $room->getName()]);
            }
        }

        /** @var Meeting */
        $meeting = $room->getMeeting();

        // if manual meeting is not running - remove, recreate?
        $bbb                 = new BigBlueButton();
        $createMeetingParams = new CreateMeetingParameters($meeting->id, $room->getName());
        $createMeetingParams->setModeratorPassword($meeting->getModeratorPassword());
        $createMeetingParams->setAttendeePassword($meeting->getAttendeePassword());
        $response = $bbb->createMeeting($createMeetingParams);

        $moderator = $room->getCreator()->getId() == $user->getId() || $room->getModerators()->contains($user);

        $joinMeetingParams = new JoinMeetingParameters($meeting->id, $user->getFullName(),
            $moderator ? $response->getModeratorPassword() : $response->getAttendeePassword());
        $joinMeetingParams->setRedirect(true);
        $url = $bbb->getJoinMeetingURL($joinMeetingParams);

        return redirect($url);
        // $response = $bbb->isMeetingRunning(new IsMeetingRunningParameters($room->getMeeting()->id));

        // return new JsonResponse(['running' => $response->isRunning()]);
    }

    /**
     * @param $roomId
     * @param Request $request
     * @param EntityManagerInterface $em
     * @Post("/room/{roomId}/add_moderator", middleware="web")
     * @Middleware("auth")
     */
    public function addModerator($roomId, Request $request, EntityManagerInterface $em) {
        /** @var User */
        $user = Auth::user();

        $moderatorId = $request->get('moderator_id');

        $roomsRepository = $em->getRepository(Room::class);
        $usersRepository = $em->getRepository(User::class);

        /** @var Room */
        $room = $roomsRepository->find($roomId);

        if ($room->getCreator()->getId() != $user->getId()) {
            abort(403);
        }

        /** @var User */
        $moderator = $usersRepository->find($moderatorId);

        $room->addModerator($moderator);

        $em->flush();

        return new JsonResponse(['result' => 'ok']);
    }

    /**
     * @param $roomId
     * @param Request $request
     * @param EntityManagerInterface $em
     * @Post("/room/{roomId}/remove_moderator", middleware="web")
     * @Middleware("auth")
     */
    public function removeModerator($roomId, Request $request, EntityManagerInterface $em) {
        /** @var User */
        $user = Auth::user();

        $moderatorId = $request->get('moderator_id');

        $roomsRepository = $em->getRepository(Room::class);
        $usersRepository = $em->getRepository(User::class);

        /** @var Room */
        $room = $roomsRepository->find($roomId);

        if ($room->getCreator()->getId() != $user->getId()) {
            abort(403);
        }

        /** @var User */
        $moderator = $usersRepository->find($moderatorId);

        $room->removeModerator($moderator);

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

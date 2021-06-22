<?php

namespace App\Console\Commands;

use App\Schedule;
use App\Service\MeetingsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Console\Command;

class CreateScheduledMeetings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CreateScheduledMeetings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MeetingsService
     */
    private $meetingsService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->meetingsService = new MeetingsService($em);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rsm = new ResultSetMappingBuilder($this->em);
        $rsm->addRootEntityFromClassMetadata(Schedule::class, 's');
        $query = $this->em->createNativeQuery(
            "SELECT * FROM shedules s WHERE current_time + interval '3h' > s.start_time - interval '15m' AND current_time + interval '3h' < s.start_time",
            $rsm);

        $schedules = $query->getResult();
        foreach ($schedules as $schedule) {
            if ($schedule->getRoom()->getMeeting() == null) {
                $this->meetingsService->createMeeting($schedule->getRoom());
            }
        }

        return 0;
    }
}

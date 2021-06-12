<?php

namespace App\Console\Commands;

use App\Meeting;
use BigBlueButton\BigBlueButton;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Console\Command;

class ClearFinishedMeetings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ClearFinishedMeetings';

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $repository = $this->em->getRepository(Meeting::class);
        $localMeetings = $repository->findBy(['duration' => 0]);
        $bbb = new BigBlueButton();
        $remoteMeetings = $bbb->getMeetings()->getMeetings();
        $indexed = [];
        foreach ($remoteMeetings as $rm) {
            $indexed[$rm->getMeetingId()] = $rm;
        }

        $localMeetings = array_filter($localMeetings, function(Meeting $v, $k) use ($indexed) {
            $key = $v->getId();
            return !array_key_exists($key, $indexed) || !$indexed[$key]->isRunning();
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($localMeetings as $m) {
            $this->em->remove($m);
        }
        $this->em->flush();
        return 0;
    }
}

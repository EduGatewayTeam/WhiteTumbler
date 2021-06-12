<?php


namespace App;

use App\Util\FilteredObjectsArray;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Room
 * @package App
 *
 * @ORM\Entity
 * @ORM\Table(name="rooms")
 */
class Room implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    public $id;

    /**
     * @var string
     * @ORM\Column
     */
    public $name;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="rooms")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $creator;

    /**
     * @var Meeting
     * @ORM\OneToOne(targetEntity="Meeting", mappedBy="room", cascade={"all"})
     */
    protected $meeting;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="User", inversedBy="moderatingRooms", fetch="EAGER")
     * @ORM\JoinTable(name="rooms_moderators", joinColumns={@ORM\JoinColumn(onDelete="cascade")})
     */
    protected $moderators;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="room", cascade={"all"}, orphanRemoval=true)
     */
    protected $schedules;

    public function __construct()
    {
        $this->moderators = new ArrayCollection();
        $this->schedules = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param User $creator
     */
    public function setCreator(User $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @return mixed
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * @param mixed $meeting
     */
    public function setMeeting($meeting) {
        $this->meeting = $meeting;
    }

    public function addModerator(User $user)
    {
        $user->addModeratingRoom($this);
        $this->moderators[] = $user;
    }

    public function removeModerator(User $user)
    {
        if ($this->moderators->contains($user)) {
            $user->removeModeratingRoom($this);
            $this->moderators->removeElement($user);
        }
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getModerators() {
        return $this->moderators;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'schedule' => $this->getSchedules()->toArray(),
            'moderators' => (new FilteredObjectsArray($this->moderators->toArray()))->with(['id', 'name', 'surname', 'patronymic'])->get()
        ];
    }

    public function addSchedule(Schedule $schedule) {
        $schedule->setRoom($this);
        $this->schedules[] = $schedule;
    }

    public function clearSchedules() {
        $this->schedules->clear();
    }

    public function getSchedules() {
        return $this->schedules;
    }
}

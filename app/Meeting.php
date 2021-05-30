<?php


namespace App;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Meeting
 * @package App
 *
 * @ORM\Entity
 * @ORM\Table(name="meetings")
 */
class Meeting
{

    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    public $id;

    /**
     * @var Room
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="meetings")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $room;

    /**
     * @var string
     * @ORM\Column
     */
    public $name;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $activateAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $deactivateAt;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="User", inversedBy="moderatingMeetings")
     * @ORM\JoinTable(name="meetings_moderators", joinColumns={@ORM\JoinColumn(onDelete="cascade")})
     */
    protected $moderators;

    public function __construct()
    {
        $this->moderators = new ArrayCollection();
    }

    /**
     * @return Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }

    /**
     * @param Room $room
     */
    public function setRoom(Room $room): void
    {
        $this->room = $room;
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
     * @return \DateTime
     */
    public function getActivateAt(): \DateTime
    {
        return $this->getActivateAt;
    }

    /**
     * @param \DateTime $activateAt
     */
    public function setActivateAt(\DateTime $activateAt): void
    {
        $this->activateAt = $activateAt;
    }

    /**
     * @return \DateTime
     */
    public function getDeactivateAt(): \DateTime
    {
        return $this->getDeactivateAt;
    }

    /**
     * @param \DateTime $deactivateAt
     */
    public function setDeactivateAt(\DateTime $deactivateAt = null): void
    {
        $this->deactivateAt = $deactivateAt;
    }

    /**
     * @param User $user
     */
    public function addModerator(User $user)
    {
        $user->addModeratingMeeting($this);
        $this->moderators[] = $user;
    }

    /**
     * @param User $user
     */
    public function removeModerator(User $user)
    {
        $user->removeModeratingMeeting($this);
        $this->moderators->removeElement($user);
    }

}

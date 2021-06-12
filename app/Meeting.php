<?php


namespace App;

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
     * @ORM\OneToOne(targetEntity="Room", inversedBy="meetings")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $room;

    /**
     * @var string
     * @ORM\Column
     */
    protected $attendeePassword;

    /**
     * @var string
     * @ORM\Column
     */
    protected $moderatorPassword;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    public $startTime;

    /**
     * @var integer
     * @ORM\Column
     */
    public $duration;


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
        $room->setMeeting($this);
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
     * Get the value of attendeePassword
     *
     * @return  string
     */
    public function getAttendeePassword()
    {
        return $this->attendeePassword;
    }

    /**
     * Set the value of attendeePassword
     *
     * @param  string  $attendeePassword
     *
     * @return  self
     */
    public function setAttendeePassword(string $attendeePassword)
    {
        $this->attendeePassword = $attendeePassword;

        return $this;
    }

    /**
     * Get the value of moderatorPassword
     *
     * @return  string
     */
    public function getModeratorPassword()
    {
        return $this->moderatorPassword;
    }

    /**
     * Set the value of moderatorPassword
     *
     * @param  string  $moderatorPassword
     *
     * @return  self
     */
    public function setModeratorPassword(string $moderatorPassword)
    {
        $this->moderatorPassword = $moderatorPassword;

        return $this;
    }

    /**
     * Get the value of startTime
     *
     * @return  \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set the value of startTime
     *
     * @param  \DateTime  $startTime
     *
     * @return  self
     */
    public function setStartTime(\DateTime $startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get the value of duration
     *
     * @return  integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set the value of duration
     *
     * @param  integer  $duration
     *
     * @return  self
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }
}

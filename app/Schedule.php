<?php


namespace App;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Class Meeting
 * @package App
 *
 * @ORM\Entity
 * @ORM\Table(name="shedules", indexes={@ORM\Index(name="room_join_index", columns={"room_id"})})
 */
class Schedule implements JsonSerializable
{
    /**
     * @var Room
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="meetings")
     * @ORM\JoinColumn(name="room_id", onDelete="cascade")
     */
    protected $room;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column
     */
    protected $weekDay;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column
     */
    protected $weekType;

    /**
     * @var \DateTime
     * @ORM\Column(type="time", nullable=false)
     */
    public $startTime;

    /**
     * @var \DateTime
     * @ORM\Column(type="time", nullable=true)
     */
    public $endTime;

    public function __construct(string $weekType, int $weekDay, string $startTime, string $endTime) {
        $this->weekType = $weekType;
        $this->weekDay = $weekDay;
        $this->startTime = DateTime::createFromFormat('H:i:s', $startTime);
        $this->endTime = DateTime::createFromFormat('H:i:s', $endTime);
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

    public function getStartTime() {
        return $this->startTime;
    }

    public function getEndTime() {
        return $this->endTime;
    }

    public function jsonSerialize()
    {
        return [
            'day_type' => $this->weekType,
            'week_day' => $this->weekDay,
            'time_start' => $this->startTime->format('H:i:s'),
            'time_end' => $this->endTime->format('H:i:s'),
        ];
    }


}

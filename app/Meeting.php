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
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="meetings")
     */
    protected $room;

    /**
     * @var string
     * @ORM\Column
     */
    public $name;

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

}

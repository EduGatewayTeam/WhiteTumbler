<?php


namespace App;

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
     * @var integer id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     */
    protected $creator;

    /**
     * @ORM\OneToMany(targetEntity="Meeting", mappedBy="room")
     */
    protected $meetings;

    public function __construct()
    {
        $this->meetings = new ArrayCollection();
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
     * @return mixed
     */
    public function getMeetings()
    {
        return $this->meetings;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'meetings' => $this->getMeetings()->toArray()
        ];
    }
}

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
     * @ORM\OneToMany(targetEntity="Meeting", mappedBy="room")
     */
    protected $meetings;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="moderatingRooms")
     * @ORM\JoinTable(name="rooms_moderators", joinColumns={@ORM\JoinColumn(onDelete="cascade")})
     */
    protected $moderators;

    public function __construct()
    {
        $this->meetings = new ArrayCollection();
        $this->moderators = new ArrayCollection();
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
    public function getMeetings()
    {
        return $this->meetings;
    }

    public function addModerator(User $user)
    {
        $user->addModeratingRoom($this);
        $this->moderators[] = $user;
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

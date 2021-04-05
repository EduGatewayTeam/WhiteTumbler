<?php


namespace App;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Moderator
 * @package App
 *
 * @ORM\Entity
 * @ORM\Table(name="moderators")
 */
class Moderator implements \JsonSerializable
{

    /**
     * @ORM\Id
     * @var Room
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="moderators")
     */
    protected $room;

    /**
     * @ORM\Id
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="moderators")
     */
    protected $user;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $registeredAt;

    public function __construct()
    {
        $this->setRegisteredAt(new DateTime());
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt(): \DateTime
    {
        return $this->registeredAt;
    }

    /**
     * @param \DateTime $registeredAt
     */
    public function setRegisteredAt(\DateTime $registeredAt): void
    {
        $this->registeredAt = $registeredAt;
    }

    public function jsonSerialize()
    {
        return [
            'room_id' => $this->room->getId(),
            'user_id' => $this->user->getAuthIdentifier(),
            'registeredAt' => $this->registeredAt
        ];
    }
}

<?php


namespace App;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;
use VertigoLabs\DoctrineFullTextPostgres\ORM\Mapping\TsVector;
use Doctrine\Orm\Mapping\Column;

/**
 * Class User
 * @package App
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements Authenticatable
{

    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    public $id;

    /**
     * @ORM\Column(name="remember_token", type="string", nullable=true)
     */
    public $rememberToken;

    /**
     * @var string
     * @ORM\Column
     */
    public $name;

    /**
     * @var string
     * @ORM\Column
     */
    public $surname;

    /**
     * @var string
     * @ORM\Column
     */
    public $patronymic;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="creator")
     */
    public $rooms;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Room", mappedBy="moderators", fetch="EAGER")
     */
    public $moderatingRooms;

    /**
     * @TsVector(source="prepareTsVector")
     */
    public $tsvector;

    public function __construct($id, $name, $surname, $patronymic)
    {
        $this->id = $id;
        $this->name = ucfirst($name);
        $this->surname = ucfirst($surname);
        $this->patronymic = ucfirst($patronymic);
        $this->rooms = new ArrayCollection();
        $this->moderatingRooms = new ArrayCollection();
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getPassword()
    {
        return '';
    }

    public function getAuthPassword()
    {
        return '';
    }

    /**
     * Get the token value for the "remember me" session.
     * @return string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     *
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->rememberToken = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'rememberToken';
    }

    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $name
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Get the value of patronymic
     *
     * @return  string
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Set the value of patronymic
     *
     * @param  string  $patronymic
     */
    public function setPatronymic(string $patronymic)
    {
        $this->patronymic = $patronymic;
    }


    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * @return string
     */
    public function getAbbreviatedFullName(): string
    {
        return $this->surname.' '.$this->name[0].'.';
    }

    /**
     * @param Room $room
     */
    public function addModeratingRoom(Room $room)
    {
        $this->moderatingRooms[] = $room;
    }

    /**
     * @param Room $room
     */
    public function removeModeratingRoom(Room $room)
    {
        $this->moderatingRooms->removeElement($room);
    }


    public function prepareTsVector() {
        return [$this->name, $this->surname, $this->patronymic];
    }

}

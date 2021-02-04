<?php


namespace App;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class User
 * @package App\Models
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
    protected $id;

    /**
     * @ORM\Column(name="remember_token", type="string", nullable=true)
     */
    protected $rememberToken;

    /**
     * @var string
     * @ORM\Column
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="creator")
     */
    protected $rooms;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->rooms = new ArrayCollection();
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

}

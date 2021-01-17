<?php


namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Room
 * @package App
 *
 * @ORM\Entity
 * @ORM\Table(name="rooms")
 */
class Room
{
    /**
     * @var integer id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

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
}

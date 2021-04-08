<?php

namespace App;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Settings
 * @package App
 *
 * @ORM\Entity
 * @ORM\Table(name="settings")
 */
class Settings implements \JsonSerializable
{
    /**
     * @var integer id
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $mute_on_startup;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $expect_moderator;

    public function __construct($mute_on_startup, $expect_moderator)
    {
        $this->set_mute_on_startup($mute_on_startup);
        $this->set_expect_moderator($expect_moderator);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function get_mute_on_startup(): bool
    {
        return $this->mute_on_startup;
    }

    /**
     * @return bool
     */
    public function get_expect_moderator(): bool
    {
        return $this->expect_moderator;
    }

    /**
     * @param bool $mute_on_startup
     */
    public function set_mute_on_startup(bool $mute_on_startup): void
    {
        $this->mute_on_startup = $mute_on_startup;
    }

    /**
     * @param bool $expect_moderator
     */
    public function set_expect_moderator(bool $expect_moderator): void
    {
        $this->expect_moderator = $expect_moderator;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'expect_moderator' => $this->expect_moderator,
            'mute_on_startup' => $this->mute_on_startup
        ];
    }
}

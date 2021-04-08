<?php


namespace App;


use Doctrine\ORM\Mapping as ORM;
use App\Settings;

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
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $activateAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $deactivateAt;

    /**
     * @var Settings
     * @ORM\ManyToOne(targetEntity="Settings", inversedBy="meeting")
     */
    protected $settings;

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

    /**
     * @return \DateTime
     */
    public function getActivateAt(): \DateTime
    {
        return $this->getActivateAt;
    }

    /**
     * @param \DateTime $activateAt
     */
    public function setActivateAt(\DateTime $activateAt): void
    {
        $this->activateAt = $activateAt;
    }

    /**
     * @return \DateTime
     */
    public function getDeactivateAt(): \DateTime
    {
        return $this->getDeactivateAt;
    }

    /**
     * @param \DateTime $deactivateAt
     */
    public function setDeactivateAt(\DateTime $deactivateAt = null): void
    {
        $this->deactivateAt = $deactivateAt;
    }

    /**
     * @return Settings
     */
    public function getSettings(): Settings
    {
        return $this->settings;
    }

    /**
     * @param Settings $settings
     */
    public function setSettings(Settings $settings): void
    {
        $this->settings = $settings;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'settings' => $this->getSettings(),
            'activateAt' => $this->getActivateAt(),
            'deactivateAt' => $this->getDeactivateAt()
        ];
    }
}

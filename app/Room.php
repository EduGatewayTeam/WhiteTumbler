<?php


namespace App;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Settings;

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
     * @ORM\OneToMany(targetEntity="Meeting", mappedBy="room")
     */
    protected $meetings;

    /**
     * @ORM\OneToMany(targetEntity="Moderator", mappedBy="room")
     */
    protected $moderators;
    
    /**
     * @var Settings
     * @ORM\ManyToOne(targetEntity="Settings", inversedBy="room")
     */
    protected $default_meeting_settings;

    
    public function __construct()
    {
        $this->meetings = new ArrayCollection();
        $this->moderators = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Settings
     */
    public function get_default_meeting_settings(): Settings
    {
        return $this->default_meeting_settings;
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
     * @param Settings $settings
     */
    public function setDefaultMeetingSettings(Settings $settings): void
    {
        $this->default_meeting_settings = $settings;
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

    /**
     * @return mixed
     */
    public function getModerators()
    {
        return $this->moderators;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'meetings' => $this->getMeetings()->toArray(),
            'moderators' => $this->getModerators()->toArray(),
            'default_meeting_settings' => $this->default_meeting_settings
        ];
    }
}

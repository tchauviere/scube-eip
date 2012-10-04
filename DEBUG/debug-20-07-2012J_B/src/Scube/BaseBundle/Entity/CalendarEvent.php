<?php
// src/Scube/BaseBundle/Entity/CalendarEvent.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="calendar_event")
 */
class CalendarEvent
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $start
     *
     * @ORM\Column(name="start", type="string", length=255)
     */
    private $start;

    /**
     * @var string $end
     *
     * @ORM\Column(name="end", type="string", length=255)
     */
    private $end;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var boolean $allday
     *
     * @ORM\Column(name="allday", type="boolean")
     */
    private $allday;

	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
	private $userCreator;

	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
	private $guestList;
	 
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set start
     *
     * @param string $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Get start
     *
     * @return string 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param string $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * Get end
     *
     * @return string 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set allday
     *
     * @param boolean $allday
     */
    public function setAllday($allday)
    {
        $this->allday = $allday;
    }

    /**
     * Get allday
     *
     * @return boolean 
     */
    public function getAllday()
    {
        return $this->allday;
    }
    public function __construct()
    {
        $this->guestList = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set userCreator
     *
     * @param Scube\BaseBundle\Entity\User $userCreator
     */
    public function setUserCreator(\Scube\BaseBundle\Entity\User $userCreator)
    {
        $this->userCreator = $userCreator;
    }

    /**
     * Get userCreator
     *
     * @return Scube\BaseBundle\Entity\User 
     */
    public function getUserCreator()
    {
        return $this->userCreator;
    }

    /**
     * Add guestList
     *
     * @param Scube\BaseBundle\Entity\User $guestList
     */
    public function addUser(\Scube\BaseBundle\Entity\User $guestList)
    {
        $this->guestList[] = $guestList;
    }

    /**
     * Get guestList
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGuestList()
    {
        return $this->guestList;
    }
}
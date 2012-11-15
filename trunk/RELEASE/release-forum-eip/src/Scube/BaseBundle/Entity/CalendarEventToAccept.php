<?php
// src/Scube/BaseBundle/Entity/CalendarEventToAccept.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="calendar_event_to_accept")
 */
class CalendarEventToAccept
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $userCreator;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\CalendarEvent")
     * @ORM\JoinColumn(name="event_calendar", referencedColumnName="id")
     */
    protected $event;

   

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
     * Set event
     *
     * @param Scube\BaseBundle\Entity\CalendarEvent $event
     */
    public function setEvent(\Scube\BaseBundle\Entity\CalendarEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Get event
     *
     * @return Scube\BaseBundle\Entity\CalendarEvent 
     */
    public function getEvent()
    {
        return $this->event;
    }
}
<?php
// src/Scube/BaseBundle/Entity/Calendar.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="calendar")
 */
class Calendar
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\CalendarEvent")
	 * @ORM\JoinTable(name="calendar_events")
     */ 
    protected $calendar_events;
	
	public function __construct()
    {
        $this->calendar_events = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add calendar_events
     *
     * @param Scube\BaseBundle\Entity\CalendarEvent $calendarEvents
     */
    public function addCalendarEvent(\Scube\BaseBundle\Entity\CalendarEvent $calendarEvents)
    {
        $this->calendar_events[] = $calendarEvents;
    }

    /**
     * Get calendar_events
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCalendarEvents()
    {
        return $this->calendar_events;
    }
}
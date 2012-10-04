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
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\CalendarEventToAccept")
	 * @ORM\JoinTable(name="calendar_events_to_accept")
     */ 
    protected $calendar_events_to_accept;
	
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

    /**
     * Add calendar_events_to_accept
     *
     * @param Scube\BaseBundle\Entity\CalendarEventToAccept $calendarEventsToAccept
     */
    public function addCalendarEventToAccept(\Scube\BaseBundle\Entity\CalendarEventToAccept $calendarEventsToAccept)
    {
        $this->calendar_events_to_accept[] = $calendarEventsToAccept;
    }

    /**
     * Get calendar_events_to_accept
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCalendarEventsToAccept()
    {
        return $this->calendar_events_to_accept;
    }

    /**
     * Get calendar_event_to_accept
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCalendarEventToAccept()
    {
        return $this->calendar_event_to_accept;
    }
}
<?php

namespace Scube\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\CalendarEvent;

class CalendarController extends Controller
{
    
    public function indexAction()
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
        return $this->render('ScubeCalendarBundle:Calendar:index.html.twig', array('user'=>$user));
    }
	
	public function addeventAction()
	{
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
	
		$array = $_POST['array_events'];
		
		
		$title = $array['0'];
		$start = $array['1'];
		$end = $array['2'];
		$allDay = $array['3'];
		
		$em = $this->getDoctrine()->getEntityManager();
		
		$Event = new CalendarEvent();
		$Event->setTitle($title);
	
		if($allDay == "true")
			$Event->setAllDay(1);
			else
		$Event->setAllDay(0);
		
		$Event->setStart($start);
		$Event->setEnd($end);
		
		
		$em->persist($Event);
		$user->getCalendar()->addCalendarEvent($Event);
		$em->flush();
			
		 return $this->render('ScubeCalendarBundle:Calendar:index.html.twig', array('user'=>$user));
	}
}

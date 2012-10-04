<?php

namespace Scube\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\CalendarEvent;
use Scube\BaseBundle\Entity\CalendarEventToAccept;

class CalendarController extends Controller
{
    
    public function indexAction()
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
        return $this->render('ScubeCalendarBundle:Calendar:index.html.twig', array('user'=>$user));
    }
	
	public function acceptEventAction()
	{
	}
	
	public function refuseeventAction()
	{
	}
	
	public function displayeventtoacceptAction()
	{
	$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		return  $this->render('ScubeCalendarBundle:Calendar:acceptEvent.html.twig', array('user'=>$user));
	}
	
	public function editeventAction()
	{
		$array = $_POST['edit_event'];		
		$id = $array['0'];
		$title = $array['1'];
		$start = $array['2'];
		$end = $array['3'];
		$allday = $array['4'];
		
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));

		$em = $this->getDoctrine()->getEntityManager();
		$event = $em->getRepository('ScubeBaseBundle:CalendarEvent')->find($id);
	
		if (!$event) {
			throw $this->createNotFoundException('No event found for id '.$id);
		}
		
		$event->setTitle($title);
	
		if($allDay == "true")
			$event->setAllDay(1);
			else
		$event->setAllDay(0);
		
		$event->setStart($start);
		$event->setEnd($end);
		$em->flush();
		
		 return $this->render('ScubeCalendarBundle:Calendar:index.html.twig', array('user'=>$user));
	}
	
	public function deleteeventAction()
	{

		$session = $this->getRequest()->getSession();
		
		$array = $_POST['delete_event'];
		
		
		$id = $array['0'];
		
		//$session = $this->getRequest()->getSession();
		$em = $this->getDoctrine()->getEntityManager();
		$event = $em->getRepository('ScubeBaseBundle:CalendarEvent')->find($id);
	
		if (!$event) {
			throw $this->createNotFoundException('No event found for id '.$id);
		}
		
		$em->remove($event);
		$em->flush();
		
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
		$group = $array['4'];
		
		
		
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
	
		if ($group)
		{
			$connectionGroup = "";
			$connectionGroupList = $user->getConnectionsGroups();
			for ($i=0; $i<sizeof($connectionGroupList);$i++)
			{
				$connectionGroup = $connectionGroupList[$i];
				if ($connectionGroup->getName() == $group)
					break;
			}
			if ($connectionGroup)
			{
				$listUser = $connectionGroup->getUsers();
				$EventToAccept = new CalendarEventToAccept();

				$EventToAccept->setUserCreator($user);
				$EventToAccept->setEvent($Event);
				$em->persist($EventToAccept);
				$em->flush();
				foreach ($listUser as $guest)
				{
					//$guest->getCalendar()->addCalendarEvent($Event);
					$guest->getCalendar()->addCalendarEventToAccept($EventToAccept);
				}
				$em->flush();
			}
				
		}
		
		 return $this->render('ScubeCalendarBundle:Calendar:index.html.twig', array('user'=>$user));
	}
}

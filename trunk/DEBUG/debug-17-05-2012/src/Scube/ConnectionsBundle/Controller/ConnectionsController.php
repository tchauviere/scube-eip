<?php

namespace Scube\ConnectionsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ConnectionsController extends Controller
{
    
    public function indexAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
        
		return $this->render('ScubeConnectionsBundle:Connections:index.html.twig', array('user'=>$user));
    }
	public function searchAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT u FROM ScubeBaseBundle:User u ORDER BY u.firstname ASC");
		$users_list = $query->getResult();
		
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
        
		return $this->render('ScubeConnectionsBundle:Connections:search.html.twig', array('user'=>$user, 'users_list'=>$users_list));
    }
	public function WidgetAction()
    {
		return $this->render('ScubeConnectionsBundle:Connections:widget.html.twig');
    }
}

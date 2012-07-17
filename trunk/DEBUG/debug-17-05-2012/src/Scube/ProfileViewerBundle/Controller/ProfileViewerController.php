<?php

namespace Scube\ProfileViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;

class ProfileViewerController extends Controller
{
    
    public function indexAction($id_user)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$user_to_display = $repository->find($id_user);
		
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:index.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display));
    }
	public function newsfeedAction($id_user)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$user_to_display = $repository->find($id_user);
		
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:newsfeed.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display));
    }
	public function infosAction($id_user)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$user_to_display = $repository->find($id_user);
		
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:infos.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display));
    }
	public function picsAction($id_user)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$user_to_display = $repository->find($id_user);
		
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:pics.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display));
    }
}

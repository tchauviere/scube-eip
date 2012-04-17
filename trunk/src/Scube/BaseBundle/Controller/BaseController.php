<?php

namespace Scube\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Scube\BaseBundle\Entity\Users;

class BaseController extends Controller
{
    
    public function indexAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		/* User logged -> display the index */
		if ($session->get('user'))
		{
			$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:Users');
			$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
						
			return $this->render('ScubeBaseBundle:Base:index.html.twig', array('user' => $user));
		}
		/* User not logged -> display login form */
		else
		{
			$users = new Users();
		
			$form = $this->createFormBuilder($users)
				->add('Email', 'email')
				->add('Password', 'password')
				->getForm();
				
			if ($request->getMethod() == 'POST') {
				$form->bindRequest($request);
		
				if ($form->isValid()) {
					
					$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:Users');
					$LoggingUser = $form->getData();
					$user = $repository->findOneBy(array('email' => $LoggingUser->getEmail(), 'password' => $LoggingUser->getPassword()));
					if ($user)
					{
						$user->getApps();
						$session->set('user', $user);
						return $this->redirect($this->generateUrl('_homepage'));
					}
					return $this->render('ScubeBaseBundle:Base:login.html.twig', array('form' => $form->createView(), 'error' => true));
				}
			}
			return $this->render('ScubeBaseBundle:Base:login.html.twig', array('form' => $form->createView(), "error"=>false));
		}
    }
	
	public function registerAction(Request $request)
    {
		$users = new Users();
		
		$form = $this->createFormBuilder($users)
            ->add('Firstname', 'text')
            ->add('Surname', 'text')
			->add('Email', 'email')
			->add('Password', 'password')
			->add('Birthday', 'birthday')
			->add('Gender', 'choice', array('choices' => array('male' => 'Male', 'female' => 'Female')))
            ->getForm();
			
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
				
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($users);
				$em->flush();
				
				return $this->render('ScubeBaseBundle:Base:register.html.twig', array("success"=>true));
			}
		}
			
		return $this->render('ScubeBaseBundle:Base:register.html.twig', array('form' => $form->createView(), "success"=>false));
    }
	
	public function logoutAction(Request $request)
    {
		$this->getRequest()->getSession()->remove('user');
		return $this->redirect($this->generateUrl('_homepage'));
    }
	
	public function frame_profileAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:Users');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));					
		return $this->render('ScubeBaseBundle:Base:frame_profile.html.twig', array('user' => $user));
    }
}

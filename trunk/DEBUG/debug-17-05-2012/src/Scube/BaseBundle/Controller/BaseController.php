<?php

namespace Scube\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;
use Scube\BaseBundle\Entity\BaseInterface;

class BaseController extends Controller
{
    /* Main Page - Index */
    public function indexAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		/* User logged -> display the index */
		if ($session->get('user'))
		{
			$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
			$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
						
			return $this->render('ScubeBaseBundle:Base:index.html.twig', array('user' => $user));
		}
		/* User not logged -> display login form */
		else
		{
			$user = new User();
		
			$form = $this->createFormBuilder($user)
				->add('Email', 'email')
				->add('Password', 'password')
				->getForm();
				
			if ($request->getMethod() == 'POST') {
				$form->bindRequest($request);
		
				if ($form->isValid()) {
					
					$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
					$LoggingUser = $form->getData();
					$user = $repository->findOneBy(array('email' => $LoggingUser->getEmail(), 'password' => $LoggingUser->getPassword()));
					if ($user)
					{
						$session->set('user', $user);
						return $this->redirect($this->generateUrl('_homepage'));
					}
					return $this->render('ScubeBaseBundle:Base:login.html.twig', array('form' => $form->createView(), 'error' => true));
				}
			}
			$allow_registration = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "allow_registration"));
			if (!$allow_registration || $allow_registration->getValue() == "0")
				$allow_registration = false;
			return $this->render('ScubeBaseBundle:Base:login.html.twig', array('allow_registration'=>$allow_registration, 'form' => $form->createView(), "error"=>false));
		}
    }
	public function logoutAction(Request $request)
    {
		$this->getRequest()->getSession()->remove('user');
		return $this->redirect($this->generateUrl('_homepage'));
    }
	
	public function registerAction(Request $request)
    {
		$allow_registration = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "allow_registration"));
		if (!$allow_registration || $allow_registration->getValue() == "0")
			return $this->render('ScubeBaseBundle:Base:register.html.twig', array("allow_registration"=>false, "success"=>false));
		
		$user = new User();
		
		$form = $this->createFormBuilder($user)
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
				/* Set profile object */
				$profile = new UserProfile();
				/* Set interface object */
				$interface = new BaseInterface();
				/* Set group object from database */
				$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:PermissionsGroup');
				$default_group = $repository->findOneBy(array('name' => "default"));
				
				
				$user->setProfile($profile);
				$user->setBaseInterface($interface);
				$user->setPermissionsGroup($default_group);
				
				
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($profile);
				$em->persist($interface);
				$em->persist($user);
				$em->flush();
				
				return $this->render('ScubeBaseBundle:Base:register.html.twig', array("success"=>true));
			}
		}
			
		return $this->render('ScubeBaseBundle:Base:register.html.twig', array("allow_registration"=>$allow_registration,'form' => $form->createView(), "success"=>false));
    }
	
	/* Account Edition Form */
	public function editAccountAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$form = $this->createFormBuilder($user)
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
				$em->flush();
				
				$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
				$LoggingUser = $form->getData();
				$user = $repository->findOneBy(array('email' => $LoggingUser->getEmail(), 'password' => $LoggingUser->getPassword()));
				if ($user)
				{
					$session->set('user', $user);
				}
				
				return $this->render('ScubeBaseBundle:Base:edit_account.html.twig', array('form' => $form->createView(), "success"=>true));
			}
		}
			
		return $this->render('ScubeBaseBundle:Base:edit_account.html.twig', array('form' => $form->createView(), "success"=>false));
    }
	
	/* Profile Edition Form */
	public function editProfileAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$profile = $user->getProfile();
		
		$form = $this->createFormBuilder($profile)
			->add('status', 'choice', array("required"=>false, 'choices' => array('single' => 'Single', 'married' => 'Married')))
			->add('language', 'choice', array("required"=>false, 'choices' => array('en' => 'English', 'fr' => 'Francais')))
			->add('phone_number', 'text', array("required"=>false))
			->add('native_city', 'text', array("required"=>false))
			->add('city', 'text', array("required"=>false))
			->add('address', 'text', array("required"=>false))
			->add('postal_code', 'text', array("required"=>false))
			->add('website', 'url', array("required"=>false))
            ->getForm();
			
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();
				
				return $this->render('ScubeBaseBundle:Base:edit_profile.html.twig', array('form' => $form->createView(),"success"=>true));
			}
		}
			
		return $this->render('ScubeBaseBundle:Base:edit_profile.html.twig', array('form' => $form->createView(), "success"=>false));
    }
	
	public function frame_profileAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));					
		return $this->render('ScubeBaseBundle:Base:frame_profile.html.twig', array('user' => $user));
    }
	
	/* Applications */
	public function ApplicationListAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));					
		return $this->render('ScubeBaseBundle:Base:frame_profile.html.twig', array('user' => $user));
    }
}

<?php

namespace Scube\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;

class AccountController extends Controller
{
    /* Account Edition Form */
	public function editAccountAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$form = $this->createFormBuilder($user)
            ->add('Firstname', 'text')
            ->add('Surname', 'text')
			/*->add('Email', 'email')
			->add('Password', 'password')*/
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
				if (\Scube\BaseBundle\Controller\BaseController::isMobile())
					return $this->render('ScubeBaseBundle:Base_Mobile:edit_account.html.twig', array('form' => $form->createView(), "success"=>true));
				return $this->render('ScubeAccountBundle:Account:edit_account.html.twig', array('form' => $form->createView(), "success"=>true));
			}
		}
		if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:edit_account.html.twig', array('form' => $form->createView(), "success"=>false));	
		return $this->render('ScubeAccountBundle:Account:edit_account.html.twig', array('form' => $form->createView(), "success"=>false));
    }
	
	/* Email and password Edition Form */
	public function editEmailPasswordAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$form = $this->createFormBuilder($user)
			->add('Email', 'email')
			->add('Password', 'password')
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
				return $this->render('ScubeAccountBundle:Account:edit_email_password.html.twig', array('form' => $form->createView(), "success"=>true));
			}
		}
		return $this->render('ScubeAccountBundle:Account:edit_email_password.html.twig', array('form' => $form->createView(), "success"=>false));
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
				if (\Scube\BaseBundle\Controller\BaseController::isMobile())
					return $this->render('ScubeBaseBundle:Base_Mobile:edit_profile.html.twig', array('form' => $form->createView(),"success"=>true));
				return $this->render('ScubeAccountBundle:Account:edit_profile.html.twig', array('form' => $form->createView(),"success"=>true));
			}
		}
		if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:edit_profile.html.twig', array('form' => $form->createView(), "success"=>false));	
		return $this->render('ScubeAccountBundle:Account:edit_profile.html.twig', array('form' => $form->createView(), "success"=>false));
    }
	
	/* Email and password Edition Form */
	public function editPictureAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$profile = $user->getProfile();
		
		$form = $this->createFormBuilder($profile)
			->add('picture', 'file')
            ->getForm();
			
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
				
				$folder_pics = "/profile_pics";
				
				$path_destination = \Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user);
				if ($path_destination)
				{
					$path_destination = $path_destination . $folder_pics;
					if (!file_exists($path_destination) || !is_dir($path_destination))
						mkdir($path_destination);
				}
				else
					return $this->render('ScubeAccountBundle:Account:edit_picture.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>false));
				
				$extension = $form['picture']->getData()->guessExtension();
				$final_filename = rand().'.'.$extension;
				
				$form['picture']->getData()->move($path_destination, $final_filename);
				
				$em = $this->getDoctrine()->getEntityManager();
				$profile->setPicture($this->get('request')->getBasePath() . "/" . \Scube\BaseBundle\Controller\BaseController::getUserDirectoryPath($user) . $folder_pics . "/".$final_filename);
				$em->flush();
				
				return $this->render('ScubeAccountBundle:Account:edit_picture.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>true));
			}
		}
		return $this->render('ScubeAccountBundle:Account:edit_picture.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>false));
    }
}

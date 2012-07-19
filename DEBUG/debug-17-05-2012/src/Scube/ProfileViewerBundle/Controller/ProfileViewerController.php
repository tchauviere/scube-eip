<?php

namespace Scube\ProfileViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;
use Scube\BaseBundle\Entity\News;

class ProfileViewerController extends Controller
{
    
    public function indexAction(Request $request, $id_user)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$user_to_display = $repository->find($id_user);
		if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:view_profile.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display));
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:index.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display));
    }
	public function newsfeedAction(Request $request, $id_user)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$user_to_display = $repository->find($id_user);
		
		if ($user_connected == $user_to_display)
			$auth = true;
		else
		{
			$groups = $user_to_display->getConnectionsGroups();
			$auth = false;
			foreach ($groups as $grp)
			{
				if ( ! $grp->getAuthProfileNews())
					continue;
				
				foreach ($grp->getUsers() as $usr)
				{
					if ($user_connected == $usr)
					{
						$auth = true;
						break ;
					}
				}
				if ($auth)
					break ;
			}
		}
		
		$news = new News();
		$form = $this->createFormBuilder($news)
           ->add('content_text', 'textarea')
           ->getForm();
               
               if ($request->getMethod() == 'POST') {
                       $form->bindRequest($request);
       				   
                       if ($form->isValid()) {
                       
					   		   $news->setAuthor($user_connected);
							   $news->setPostDate(new \DateTime());
								
                               $em = $this->getDoctrine()->getEntityManager();
							   $em->persist($news);
							   $user_to_display->addNews($news);
                               $em->flush();
							    if (\Scube\BaseBundle\Controller\BaseController::isMobile())
									return $this->redirect($this->generateUrl('_homepage'));
							   return $this->redirect($this->generateUrl('ScubeProfileViewerBundle_homepage', array("id_user"=>$user_to_display->getId())));
                       }
               }
		if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:news_feed.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display, "auth"=>$auth, 'form' => $form->createView(), "success"=>false));
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:newsfeed.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display, "auth"=>$auth, 'form' => $form->createView(), "success"=>false));
    }
	public function newsfeedRemoveAction(Request $request, $id_user, $id_news)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$user_to_display = $repository->find($id_user);
		
		$news = $this->getDoctrine()->getRepository('ScubeBaseBundle:News')->find($id_news);
		
		
		if (!$news) {
			throw $this->createNotFoundException('No news found for id '.$id);
		}
		
		if ($news->getAuthor() == $user_connected || $user_to_display == $user_connected)
		{
			$em = $this->getDoctrine()->getEntityManager();
			$em->remove($news);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('ScubeProfileViewerBundle_homepage', array("id_user"=>$user_to_display->getId())));
    }
	
	public function infosAction(Request $request, $id_user)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$user_to_display = $repository->find($id_user);
		
		if ($user_connected == $user_to_display)
			$auth = true;
		else
		{
			$groups = $user_to_display->getConnectionsGroups();
			$auth = false;
			foreach ($groups as $grp)
			{
				if ( ! $grp->getAuthProfileInfos())
					continue;
				
				foreach ($grp->getUsers() as $usr)
				{
					if ($user_connected == $usr)
					{
						$auth = true;
						break ;
					}
				}
				if ($auth)
					break ;
			}
		}
		
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:infos.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display, "auth"=>$auth));
    }
	public function picsAction(Request $request, $id_user)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$user_to_display = $repository->find($id_user);
		
		if ($user_connected == $user_to_display)
			$auth = true;
		else
		{
			$groups = $user_to_display->getConnectionsGroups();
			$auth = false;
			foreach ($groups as $grp)
			{
				if ( ! $grp->getAuthProfilePics())
					continue;
				
				foreach ($grp->getUsers() as $usr)
				{
					if ($user_connected == $usr)
					{
						$auth = true;
						break ;
					}
				}
				if ($auth)
					break ;
			}
		}
		
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:pics.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display, "auth"=>$auth));
    }
}

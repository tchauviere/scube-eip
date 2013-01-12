<?php

namespace Scube\ProfileViewerBundle\Controller;

use Scube\CoreBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;
use Scube\BaseBundle\Entity\News;

class ProfileViewerController extends CoreController
{
    
    public function indexAction(Request $request, $id_user)
    {
    	$this->preprocessApplication();

		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $this->user;
		$user_to_display = $repository->find($id_user);
		if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:view_profile.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display));
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:index.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display));
    }
	public function newsfeedAction(Request $request, $id_user)
    {
    	$this->preprocessApplication();

		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $this->user;
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
	
	public function postListAction(Request $request, $id_user)
    {
    	$this->preprocessApplication();

		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $this->user;
		$user_to_display = $repository->find($id_user);

		$post_list = array();
		
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

		$fb_is_connected = false;
		$fb_post_list = array();
		$fb_controller = new \Scube\FacebookBundle\Controller\FacebookController;
		$fb_controller->initController($this->getDoctrine(), $this->getRequest());

		$fb = $fb_controller->createFacebookObject();

		if ($fb_controller->checkUserAlreadyRegistered($user_to_display->getId())) {
			$fb_is_connected = true;
			$fb_post_list = $fb_controller->getUserFeed($fb, $user_to_display->getId());
		}
		
		$scube_post_list = $user_to_display->getNewsfeed();

		$post_list = array();
		
		foreach ($scube_post_list as $p) {
			$post = array();
			$post['news_id'] = $p->getId();
			$post['news_message'] = $p->getContentText();
			$post['news_date'] = $p->getPostDate();
			$post['author_img'] = $p->getAuthor()->getProfile()->getPicture();
			$post['author_id'] = $p->getAuthor()->getId();
			$post['author_firstname'] = $p->getAuthor()->getFirstname();
			$post['author_surname'] = $p->getAuthor()->getSurname();

			$post['timestamp'] = $post['news_date']->getTimestamp();

			$post['fb'] = false;
			$post_list[] = $post;
		}
		
		if ($fb_post_list)
			foreach ($fb_post_list->data as $p) {
				$post = array();
				$post['news_message'] = $p->message;
				$post['news_date'] = $p->updated_time;
				$post['author_fullname'] = $p->from->name;
				$post['timestamp'] = strtotime($post['news_date']);

				$post['fb'] = true;
				$post_list[] = $post;
			}

		$post_list = $this->array_sort($post_list, 'timestamp', SORT_DESC);
		
		
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:post_list.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display, "auth"=>$auth, "post_list"=>$post_list));
    }
	
	public function newsfeedRemoveAction(Request $request, $id_user, $id_news)
    {
    	$this->preprocessApplication();

		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $this->user;
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
    	$this->preprocessApplication();

		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $this->user;
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
		if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:view_profile.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display, "auth"=>$auth));
        return $this->render('ScubeProfileViewerBundle:ProfileViewer:infos.html.twig', array("user_connected"=>$user_connected, "user_to_display"=>$user_to_display, "auth"=>$auth));
    }
	public function picsAction(Request $request, $id_user)
    {
    	$this->preprocessApplication();
    	
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user_connected = $this->user;
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

    private function array_sort($array, $on, $order=SORT_ASC)
	{
	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }

	    return $new_array;
	}

}

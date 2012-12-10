<?php

namespace Scube\ConnectionsBundle\Controller;

use Scube\CoreBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Scube\BaseBundle\Entity\ConnectionsGroup;


class ConnectionsController extends CoreController
{
    
    public function indexAction(Request $request)
    {
    	$this->preprocessApplication();

		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $this->user;
		
		$em1 = $this->getDoctrine()->getEntityManager();
		$query = $em1->createQuery("SELECT u FROM ScubeBaseBundle:User u");
		$users_list = $query->getResult();
		
		$grp = new ConnectionsGroup();
		
		$form = $this->createFormBuilder($grp)
           ->add('name', 'text')
           ->getForm();
        if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			if ($form->isValid()) {

			    $grp->setAuthProfileNews(false);
				$grp->setAuthProfileInfos(false);
				$grp->setAuthProfilePics(false);

				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($grp);
				$user->addConnectionsGroup($grp);
				$em->flush();
				$form = $this->createFormBuilder(new ConnectionsGroup())
		           ->add('name', 'text')
		           ->getForm();
				if (\Scube\BaseBundle\Controller\BaseController::isMobile())
					 return $this->render('ScubeBaseBundle:Base_Mobile:contacts.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>true, 'users_list'=>$users_list));                             
				return $this->render('ScubeConnectionsBundle:Connections:index.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>true));
				}
            }
         if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:contacts.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>false, 'users_list'=>$users_list));
		 return $this->render('ScubeConnectionsBundle:Connections:index.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>false));
    }
	public function editGroupAction(Request $request, $id=0)
    {
    	$this->preprocessApplication();

		$grp = $this->getDoctrine()->getRepository('ScubeBaseBundle:ConnectionsGroup')->find($id);

		if (!$grp) {
			throw $this->createNotFoundException('No group found for id '.$id);
		}

		$this->checkPermissionsOnGroup($id);
		
		$form = $this->createFormBuilder($grp)
           ->add('name', 'text')
		   ->add('auth_profile_news', 'checkbox', array('required'=>false, 'label'=> "My news"))
		   ->add('auth_profile_infos', 'checkbox', array('required'=>false, 'label'=> "My infos"))
		   ->add('auth_profile_pics', 'checkbox', array('required'=>false, 'label'=> "My pictures"))
           ->getForm();
               
               if ($request->getMethod() == 'POST') {
                       $form->bindRequest($request);
       
                       if ($form->isValid()) {
                       
                               $em = $this->getDoctrine()->getEntityManager();
                               $em->flush();
							                                  
                               return $this->redirect($this->generateUrl('ConnectionsBundle_homepage'));
                       }
               }
         
		 return $this->render('ScubeConnectionsBundle:Connections:edit_group.html.twig', array('form' => $form->createView(), "group"=>$grp));
    }
	public function removeGroupAction(Request $request, $id)
    {
    	$this->preprocessApplication();

		$em = $this->getDoctrine()->getEntityManager();
		$grp = $em->getRepository('ScubeBaseBundle:ConnectionsGroup')->find($id);

		if (!$grp) {
			throw $this->createNotFoundException('No group found for id '.$id);
		}

		$this->checkPermissionsOnGroup($id);
		
		$em->remove($grp);
		$em->flush();
		return $this->redirect($this->generateUrl('ConnectionsBundle_homepage'));
    }
	public function	UsersListAction(Request $request, $group_id=false, $search="")
	{
		$this->preprocessApplication();

		if ($group_id)
			$this->checkPermissionsOnGroup($group_id);

		if (!$search)
			$search = "%";
		else
			$search = "%".str_replace(" ", "%", $search)."%";
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT u FROM ScubeBaseBundle:User u WHERE CONCAT(CONCAT(u.surname, ' '), u.firstname) LIKE :search OR CONCAT(CONCAT(u.firstname, ' '), u.surname) LIKE :search OR u.firstname LIKE :search OR u.surname LIKE :search ORDER BY u.firstname ASC")->setParameters(array('search' => $search));
		$users_list = $query->getResult();

		foreach ($users_list as $k=>$u) {
			if ($u->getId() == $this->user->getId())
				unset($users_list[$k]);
		}
		
		if ($group_id)
		{
			$grp_users = $em->getRepository('ScubeBaseBundle:ConnectionsGroup')->find($group_id)->getUsers();
		
		
			foreach ($users_list as $k=>$u)
			{
				$is_in = false;
				foreach ($grp_users as $gu)
				{
					if ($u == $gu)
						$is_in = true;
				}
				if (!$is_in)
					unset($users_list[$k]);
			}
		}
		
		return $this->render('ScubeConnectionsBundle:Connections:users_list.html.twig', array('users_list'=>$users_list, 'group_id' => $group_id, 'time' => time()));
	}
	
	public function	AddUserInGroupAction(Request $request, $id_usr=0, $id_grp=0)
	{
		$this->preprocessApplication();

		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $this->user;
		
		$target_usr = $repository->find($id_usr);
		
		$target_grp = $this->getDoctrine()->getRepository('ScubeBaseBundle:ConnectionsGroup')->find($id_grp);

		if (!$id_grp) {
			throw $this->createNotFoundException('No group found for id '.$id_grp);
		}

		$this->checkPermissionsOnGroup($id_grp);
		
		$em = $this->getDoctrine()->getEntityManager();
		$target_grp->addUser($target_usr);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ConnectionsBundle_homepage'));
	}
	public function	RemoveUserFromGroupAction(Request $request, $id_usr=0, $id_grp=0)
	{
		$this->preprocessApplication();

		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $this->user;
		
		$target_usr = $repository->find($id_usr);
		
		$target_grp = $this->getDoctrine()->getRepository('ScubeBaseBundle:ConnectionsGroup')->find($id_grp);

		if (!$id_grp) {
			throw $this->createNotFoundException('No group found for id '.$id_grp);
		}

		$this->checkPermissionsOnGroup($id_grp);
		
		foreach ($target_grp->getUsers() as $k=>$u)
		{
			if ($u == $target_usr)
				$target_grp->getUsers()->remove($k);
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();
		
		return new Response('');
	}

	private function checkPermissionsOnGroup($group_id) {
		$permission = false;
		foreach ($this->user->getConnectionsGroups() as $g) {
			if ($g->getId() == $group_id) {
				$permission = true;
				break;
			}
		}
		if (!$permission)
			throw new \Exception('Permission denied for this group');
	}
}

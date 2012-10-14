<?php

namespace Scube\AdminUserBundle\Controller;

//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\CoreBundle\Controller\CoreController;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;
use Scube\BaseBundle\Entity\Calendar;
use Scube\BaseBundle\Entity\Mailbox;
use Scube\BaseBundle\Entity\BaseInterface;
use Scube\BaseBundle\Entity\PermissionsGroup;

class AdminUserController extends CoreController
{
	
    public function indexAction()
    {
		$this->preprocessApplication();
		
		$usr_list = $this->getDoctrine()
					  	 ->getEntityManager()
					  	 ->createQuery("SELECT u FROM ScubeBaseBundle:User u ORDER BY u.email ASC")
					  	 ->getResult();
		
		$parameters = array('usr_list'=>$usr_list, 'current_usr'=>$this->user);
		
		if ($this->getRequest()->get('add'))
			$parameters['add'] = true;
		if ($this->getRequest()->get('remove'))
			$parameters['remove'] = true;
						 
		return $this->render('ScubeAdminUserBundle:AdminUser:users.html.twig', $parameters);
    }
	public function addUserAction(Request $request)
    {
		$this->preprocessApplication();
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT g FROM ScubeBaseBundle:PermissionsGroup g ORDER BY g.name ASC");
		$all_groups = $query->getResult();
		
		$user = new User();
		$form = $this->createFormBuilder($user)
			->add('permissionsGroup', 'entity', array('class' => 'ScubeBaseBundle:PermissionsGroup', 'property' => 'name', 'label' => "Group"))
            ->add('Firstname', 'text')
            ->add('Surname', 'text')
			->add('Email', 'email')
			->add('Password', 'password')
			->add('Birthday', 'birthday')
			->add('Gender', 'choice', array('choices' => array('male' => 'Male', 'female' => 'Female')))
			->add('Blocked', 'choice', array('choices' => array(false => 'No', true => 'Yes'), 'label' => "Blocking"))
			->add('Locale', 'choice', array('choices' => array('en' => 'English', 'fr' => 'French'), 'label' => "Language"))
            ->getForm();
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
				$test_email_user = $this->getDoctrine()->getRepository('ScubeBaseBundle:User')->findOneBy(array('email' => $user->getEmail()));
				if ($test_email_user)
					return $this->render('ScubeAdminUserBundle:AdminUser:add_user.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>false, "error_email"=>true));
					
				/* Set profile object */
				$profile = new UserProfile();
				/* Set interface object */
				$interface = new BaseInterface();
				/* Set calendar object */
				$calendar = new Calendar();
				/* Set mailbox object */
				$mailbox = new Mailbox();
				
				$user->setOnline(false);
				$user->setDateRegister(new \DateTime());
				$user->setDateLastAccess(new \DateTime());
				
				$user->setProfile($profile);
				$user->setBaseInterface($interface);
				$user->setCalendar($calendar);
				$user->setMailbox($mailbox);
				
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($profile);
				$em->persist($interface);
				$em->persist($calendar);
				$em->persist($mailbox);
				$em->persist($user);
				$em->flush();
				
				\Scube\BaseBundle\Controller\BaseController::createUserDirectory($this->get('kernel'), $user);
				
				return $this->redirect($this->generateUrl('AdminUserBundle_homepage', array('add'=>true)));
			}
		}
			
		return $this->render('ScubeAdminUserBundle:AdminUser:add_user.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>false));
	}
	public function editUserAction(Request $request, $id)
    {
		$this->preprocessApplication();
		$user = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:User')
						->find($id);
						
		if (!$user) {
			throw $this->createNotFoundException('No user found for id '.$id);
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT g FROM ScubeBaseBundle:PermissionsGroup g ORDER BY g.name ASC");
		$all_groups = $query->getResult();
		
		$parameters = array('user'=>$user, 'success'=>false);
		
		$read_only_block = false;
		if ($user->getId() == $this->user->getId()) {
			$parameters['user_is_me'] = true;
			$read_only_block = true;
		}
		
		$form = $this->createFormBuilder($user)
			->add('permissionsGroup', 'entity', array('class' => 'ScubeBaseBundle:PermissionsGroup', 'property' => 'name', 'label' => "Group"))
            ->add('Firstname', 'text')
            ->add('Surname', 'text')
			->add('Email', 'email')
			->add('Birthday', 'birthday')
			->add('Gender', 'choice', array('choices' => array('male' => 'Male', 'female' => 'Female')))
			->add('Blocked', 'choice', array('read_only'=>$read_only_block, 'choices' => array(false => 'No', true => 'Yes'), 'label' => "Blocking"))
			->add('Locale', 'choice', array('choices' => array('en' => 'English', 'fr' => 'French'), 'label' => "Language"))
            ->getForm();
		
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
				
				$test_email_user = $this->getDoctrine()->getRepository('ScubeBaseBundle:User')->findOneBy(array('email' => $user->getEmail()));
				if ($test_email_user && $test_email_user->getId() != $user->getId())
					return $this->render('ScubeAdminUserBundle:AdminUser:edit_user.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>false, "error_email"=>true));
				
				$em = $this->getDoctrine()->getEntityManager();
				
				if (isset($parameters['user_is_me']))
					$user->setBlocked(false);
					
				$em->flush();
				
				$parameters['form'] = $form->createView();	
				$parameters['success'] = true;	
				return $this->render('ScubeAdminUserBundle:AdminUser:edit_user.html.twig', $parameters);
			}
		}
		
		$parameters['form'] = $form->createView();
		return $this->render('ScubeAdminUserBundle:AdminUser:edit_user.html.twig', $parameters);
	}
	public function removeUserAction(Request $request, $id)
    {
		$this->preprocessApplication();
		
		$em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository('ScubeBaseBundle:User')->find($id);

		if (!$user) {
			throw $this->createNotFoundException('No user found for id '.$id);
		}
		
		if ($user->getId() == $this->user->getId()) {
			throw $this->createNotFoundException('Unable to delete youself');
		}
				
		\Scube\BaseBundle\Controller\BaseController::removeUserDirectory($this->get('kernel'), $user);
		
		$em->remove($user);
		$em->flush();
				
		return $this->redirect($this->generateUrl('AdminUserBundle_homepage', array('remove'=>true)));
    }
	
	public function groupsAction(Request $request)
    {
		$this->preprocessApplication();
		
		$grp_list = $this->getDoctrine()
						 ->getEntityManager()
						 ->createQuery("SELECT g FROM ScubeBaseBundle:PermissionsGroup g ORDER BY g.name ASC")
						 ->getResult();
		
		$parameters = array('grp_list'=>$grp_list);
		
		if ($this->getRequest()->get('add'))
			$parameters['add'] = true;
		if ($this->getRequest()->get('remove'))
			$parameters['remove'] = true;
			
		return $this->render('ScubeAdminUserBundle:AdminUser:groups.html.twig', $parameters);
    }
	
	public function addGroupAction(Request $request)
    {
		$this->preprocessApplication();
		
		$error = false;
		$error_text = "";
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Application a ORDER BY a.name ASC");
		$all_applications = $query->getArrayResult();
		
		$admin_app_list = array();
		$app_list = array();
		foreach ($all_applications as $app)
		{
			if ($app['type'] == "admin")
				$admin_app_list[$app['id']] = $app['name'];
			else
				$app_list[$app['id']] = $app['name'];
		}
		
		$defaultData = array('name'=>"", 'apps'=>array(), 'admin_apps'=>array());
		
		$form = $this->createFormBuilder($defaultData)
            ->add('name', 'text')
			->add('apps', 'choice', array('choices' => $app_list, 'multiple'  => true, 'required'    => false, 'label' => "Associated applications"))
			->add('admin_apps', 'choice', array('choices' => $admin_app_list, 'multiple'  => true, 'required'    => false, 'label' => "Associated administrator applications"))
            ->getForm();
				
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			
			$edit_form = $form->getData();
			
			$grp = new PermissionsGroup();
			$grp->setLocked(false);
			
			if (strlen($edit_form['name']) < 1)
			{
				$error = true;
				$error_text = $this->get('translator')->trans("Please enter a valid name (at least 1 character)");
				return $this->render('ScubeAdminUserBundle:AdminUser:add_group.html.twig', array('form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
			}
			else
				$grp->setName($edit_form['name']);
			
			$test_name_group = $this->getDoctrine()->getRepository('ScubeBaseBundle:PermissionsGroup')->findOneBy(array('name' => $edit_form['name']));
			if ($test_name_group)
			{
				$error = true;
				$error_text = $this->get('translator')->trans("Group name already used");
				return $this->render('ScubeAdminUserBundle:AdminUser:add_group.html.twig', array('form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
			}
			
			if (!$error)
			{
				foreach ($edit_form['apps'] as $app_array)
				{
					$app = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:Application')
						->find($app_array);
					$grp->addApplication($app);
				}
				foreach ($edit_form['admin_apps'] as $app_array)
				{
					$app = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:Application')
						->find($app_array);
					$grp->addAdminApplication($app);
				}
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($grp);
				$em->flush();
			}
			
			return $this->redirect($this->generateUrl('AdminUserBundle_groups', array('add'=>true)));
		}
		
		
		
		return $this->render('ScubeAdminUserBundle:AdminUser:add_group.html.twig', array('form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
	}

	public function editGroupAction(Request $request, $id)
    {
		$this->preprocessApplication();
		$error = false;
		$error_text = "";
		
		$grp = $this->getDoctrine()
			->getRepository('ScubeBaseBundle:PermissionsGroup')
			->find($id);

		if (!$grp) {
			throw $this->createNotFoundException('No group found for id '.$id);
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Application a ORDER BY a.name ASC");
		$all_applications = $query->getArrayResult();
		
		$admin_app_list = array();
		$app_list = array();
		foreach ($all_applications as $app)
		{
			if ($app['type'] == "admin")
				$admin_app_list[$app['id']] = $app['name'];
			else
				$app_list[$app['id']] = $app['name'];
		}
		
		$apps = array();
		foreach ($grp->getApplications() as $app)
		{
			$apps[] = $app->getId();
		}
		
		$admin_apps = array();
		foreach ($grp->getAdminApplications() as $app)
		{
			$admin_apps[] = $app->getId();
		}
		
		$defaultData = array('name'=>$grp->getName(), 'apps'=>$apps, 'admin_apps'=>$admin_apps);
		
		$read_only_name = false;
		if ($grp->getLocked()) {
			$read_only_name = true;
		}
		
		$form = $this->createFormBuilder($defaultData)
            ->add('name', 'text', array('read_only'=>$read_only_name))
			->add('apps', 'choice', array('choices' => $app_list, 'multiple'  => true, 'required'    => false, 'label' => "Associated applications"))
			->add('admin_apps', 'choice', array('choices' => $admin_app_list, 'multiple'  => true, 'required'    => false, 'label' => "Associated administrator applications"))
            ->getForm();
				
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			
			$edit_form = $form->getData();
			$test_name_group = $this->getDoctrine()->getRepository('ScubeBaseBundle:PermissionsGroup')->findOneBy(array('name' => $edit_form['name']));
			
			if (strlen($edit_form['name']) < 1)
			{
				$error = true;
				$error_text = $this->get('translator')->trans("Please enter a valid name (at least 1 character)");
				return $this->render('ScubeAdminUserBundle:AdminUser:edit_group.html.twig', array('grp'=>$grp, 'form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
			}
			else if ($test_name_group && $grp->getId() != $test_name_group->getId())
			{
				$error = true;
				$error_text = $this->get('translator')->trans("Group name already used");
				return $this->render('ScubeAdminUserBundle:AdminUser:edit_group.html.twig', array('grp'=>$grp, 'form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
			}
			else if (!$read_only_name)
				$grp->setName($edit_form['name']);
			
			if (!$error)
			{
				$grp->setApplication(new \Doctrine\Common\Collections\ArrayCollection());
				$grp->setAdminApplication(new \Doctrine\Common\Collections\ArrayCollection());
				foreach ($edit_form['apps'] as $app_array)
				{
					$app = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:Application')
						->find($app_array);
					$grp->addApplication($app);
				}
				foreach ($edit_form['admin_apps'] as $app_array)
				{
					$app = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:Application')
						->find($app_array);
					$grp->addAdminApplication($app);
				}
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();
			}
			return $this->render('ScubeAdminUserBundle:AdminUser:edit_group.html.twig', array('grp'=>$grp, 'form' => $form->createView(), "success"=>true, "error"=>$error, "error_text"=>$error_text));
		}
		
		return $this->render('ScubeAdminUserBundle:AdminUser:edit_group.html.twig', array('grp'=>$grp, 'form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
    }
	
	public function removeGroupAction(Request $request, $id)
    {
		$this->preprocessApplication();
		$em = $this->getDoctrine()->getEntityManager();
		$grp = $em->getRepository('ScubeBaseBundle:PermissionsGroup')->find($id);
		if ($grp->getLocked() == false)
		{
			$grp_default = $em->getRepository('ScubeBaseBundle:PermissionsGroup')->findOneBy(array("name"=>"default"));
		
			if (!$grp) {
				throw $this->createNotFoundException('No group found for id '.$id);
			}
			if (!$grp_default) {
				throw $this->createNotFoundException('No group <default> found');
			}
			
			$query = $em->createQuery("SELECT u FROM ScubeBaseBundle:User u");
			$usr_list = $query->getResult();
			
			foreach ($usr_list as $usr)
			{
				if ($usr->getPermissionsGroup() == $grp)
					$usr->setPermissionsGroup($grp_default);
			}
			
			$em->remove($grp);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('AdminUserBundle_groups', array('remove'=>true)));
    }
}

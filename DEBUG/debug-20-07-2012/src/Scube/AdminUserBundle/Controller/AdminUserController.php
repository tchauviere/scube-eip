<?php

namespace Scube\AdminUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;
use Scube\BaseBundle\Entity\Calendar;
use Scube\BaseBundle\Entity\Mailbox;
use Scube\BaseBundle\Entity\BaseInterface;
use Scube\BaseBundle\Entity\PermissionsGroup;

class AdminUserController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT u FROM ScubeBaseBundle:User u ORDER BY u.email ASC");
		$usr_list = $query->getResult();
		return $this->render('ScubeAdminUserBundle:AdminUser:index.html.twig', array('usr_list'=>$usr_list));
    }
	public function addUserAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT g FROM ScubeBaseBundle:PermissionsGroup g ORDER BY g.name ASC");
		$all_groups = $query->getResult();
		
		$user = new User();
		$form = $this->createFormBuilder($user)
			->add('permissionsGroup', 'entity', array('class' => 'ScubeBaseBundle:PermissionsGroup', 'property' => 'name'))
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
				/* Set calendar object */
				$calendar = new Calendar();
				/* Set mailbox object */
				$mailbox = new Mailbox();
				
				$user->setOnline(false);
				$user->setBlocked(false);
				$user->setDateRegister(new \DateTime());
				$user->setDateLastAccess(new \DateTime());
				$user->setLocale($this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "default_locale"))->getValue());
				
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
				
				/*return $this->render('ScubeAdminUserBundle:AdminUser:add_user.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>true));*/
				return $this->redirect($this->generateUrl('AdminUserBundle_homepage'));
			}
		}
			
		return $this->render('ScubeAdminUserBundle:AdminUser:add_user.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>false));
	}
	public function editUserAction(Request $request, $id)
    {
		$user = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:User')
						->find($id);
						
		if (!$user) {
			throw $this->createNotFoundException('No user found for id '.$id);
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT g FROM ScubeBaseBundle:PermissionsGroup g ORDER BY g.name ASC");
		$all_groups = $query->getResult();
		
		$form = $this->createFormBuilder($user)
			->add('permissionsGroup', 'entity', array('class' => 'ScubeBaseBundle:PermissionsGroup', 'property' => 'name'))
            ->add('Firstname', 'text')
            ->add('Surname', 'text')
			->add('Email', 'email')
			->add('Birthday', 'birthday')
			->add('Gender', 'choice', array('choices' => array('male' => 'Male', 'female' => 'Female')))
            ->getForm();
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
			
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();
				
				return $this->render('ScubeAdminUserBundle:AdminUser:edit_user.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>true));
			}
		}
			
		return $this->render('ScubeAdminUserBundle:AdminUser:edit_user.html.twig', array('user'=>$user, 'form' => $form->createView(), "success"=>false));
	}
	public function removeUserAction(Request $request, $id)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository('ScubeBaseBundle:User')->find($id);
	
		if (!$user) {
			throw $this->createNotFoundException('No user found for id '.$id);
		}
		
		\Scube\BaseBundle\Controller\BaseController::removeUserDirectory($this->get('kernel'), $user);
		
		$em->remove($user);
		$em->flush();
		
		
		
		return $this->redirect($this->generateUrl('AdminUserBundle_homepage'));
    }
	
	public function groupsAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT g FROM ScubeBaseBundle:PermissionsGroup g ORDER BY g.name ASC");
		$grp_list = $query->getResult();
		return $this->render('ScubeAdminUserBundle:AdminUser:groups.html.twig', array('grp_list'=>$grp_list));
    }
	
	public function addGroupAction(Request $request)
    {
		$error = false;
		$error_text = "";
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Application a ORDER BY a.name ASC");
		$all_applications = $query->getArrayResult();
		
		$app_list = array();
		foreach ($all_applications as $app)
		{
			$app_list[$app['id']] = $app['name'];
		}
		
		$defaultData = array('name'=>"", 'apps'=>array(), 'admin_apps'=>array());
		
		$form = $this->createFormBuilder($defaultData)
            ->add('name', 'text')
			->add('apps', 'choice', array('choices' => $app_list, 'multiple'  => true, 'required'    => false))
			->add('admin_apps', 'choice', array('choices' => $app_list, 'multiple'  => true, 'required'    => false))
            ->getForm();
				
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			
			$edit_form = $form->getData();
			
			$grp = new PermissionsGroup();
			
			if (strlen($edit_form['name']) < 1)
			{
				$error = true;
				$error_text = "Please enter a valid name (at least 1 character)";
				return $this->render('ScubeAdminUserBundle:AdminUser:add_group.html.twig', array('form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
			}
			else
				$grp->setName($edit_form['name']);
			
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
			/*return $this->render('ScubeAdminUserBundle:AdminUser:add_group.html.twig', array('form' => $form->createView(), "success"=>true, "error"=>$error, "error_text"=>$error_text));*/
			return $this->redirect($this->generateUrl('AdminUserBundle_groups'));
		}
		
		
		
		return $this->render('ScubeAdminUserBundle:AdminUser:add_group.html.twig', array('form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
	}

	public function editGroupAction(Request $request, $id)
    {
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
		
		$app_list = array();
		foreach ($all_applications as $app)
		{
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
		
		$form = $this->createFormBuilder($defaultData)
            ->add('name', 'text')
			->add('apps', 'choice', array('choices' => $app_list, 'multiple'  => true, 'required'    => false))
			->add('admin_apps', 'choice', array('choices' => $app_list, 'multiple'  => true, 'required'    => false))
            ->getForm();
				
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			
			$edit_form = $form->getData();
			
			if (strlen($edit_form['name']) < 1)
			{
				$error = true;
				$error_text = "Please enter a valid name (at least 1 character)";
				return $this->render('ScubeAdminUserBundle:AdminUser:edit_group.html.twig', array('grp'=>$grp, 'form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
			}
			else
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
		$em = $this->getDoctrine()->getEntityManager();
		$grp = $em->getRepository('ScubeBaseBundle:PermissionsGroup')->find($id);
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
		return $this->redirect($this->generateUrl('AdminUserBundle_groups'));
    }
}

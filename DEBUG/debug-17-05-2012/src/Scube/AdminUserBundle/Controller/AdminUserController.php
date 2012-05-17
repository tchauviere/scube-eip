<?php

namespace Scube\AdminUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;
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
	public function editUserAction(Request $request, $id)
    {
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:PermissionsGroup');
		
		$user = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:User')
						->find($id);
						
		if (!$user) {
			throw $this->createNotFoundException('No user found for id '.$id);
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT g FROM ScubeBaseBundle:PermissionsGroup g ORDER BY g.name ASC");
		$all_groups = $query->getResult();
		
		/*$grp_list = array();
		foreach ($all_groups as $grp)
		{
			$grp_list[$grp['id']] = $grp['name'];
		}*/
		
		$form = $this->createFormBuilder($user)
			/*->add('permissionsGroup', 'choice', array('choices' => $all_groups))*/
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
	
	public function groupsAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT g FROM ScubeBaseBundle:PermissionsGroup g ORDER BY g.name ASC");
		$grp_list = $query->getResult();
		return $this->render('ScubeAdminUserBundle:AdminUser:groups.html.twig', array('grp_list'=>$grp_list));
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
				//$em->persist($grp);
				$em->flush();
			}
			return $this->render('ScubeAdminUserBundle:AdminUser:edit_group.html.twig', array('grp'=>$grp, 'form' => $form->createView(), "success"=>true, "error"=>$error, "error_text"=>$error_text));
		}
		
		
		
		return $this->render('ScubeAdminUserBundle:AdminUser:edit_group.html.twig', array('grp'=>$grp, 'form' => $form->createView(), "success"=>false, "error"=>$error, "error_text"=>$error_text));
    }
}

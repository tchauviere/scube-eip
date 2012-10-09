<?php

namespace Scube\AdminSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Scube\BaseBundle\Entity\DbSettings;

class AdminSystemController extends Controller
{
    public function indexAction()
    {
		$cur_mode = $this->getCurMode();
        return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode));
		
    }
	
	public function dbsettingsAction()
    {
	
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:DbSettings');
		$dbSettings = $repository->find(1);

		$formBuilder = $this->createFormBuilder($dbSettings);
		
		$formBuilder->add('driver',    'choice', array('choices' => array('mysql' => 'MySql (PDO)', 
																	'sqlite' => 'SQLite (PDO)',
																	'postgresql' => 'PostgreSQL (PDO)',
																	'oracleNative' => 'Oracle (native)',
																	'ibmdb2Native' => 'IBM DB2 (native)',
																	'oraclePDO' => 'Oracle (PDO)',
																	'ibmdb2PDO' => 'IBM DB2 (PDO)',
																	'sqlserver' => 'SQLServer (PDO)')))
					->add('host',   	'text')
					->add('dbname', 	'text')
					->add('user', 		'text')
					->add('password',  	'text', 	array('required'  => false))
					->add('port',  		'text', 	array('required'  => false));
		
		$form = $formBuilder->getForm();
		
		$request = $this->get('request');
		
		if ($request->getMethod() == 'POST')
		{
			$form->bindRequest($request);
			if ($form->isValid())
			{
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($dbSettings);
				$em->flush();

				return $this->render('ScubeAdminSystemBundle:AdminSystem:dbsettings.html.twig', array('form' => $form->createView(), 'updated' => true));
			}
			else
				return $this->render('ScubeAdminSystemBundle:AdminSystem:dbsettings.html.twig', array('form' => $form->createView(), 'updated' => false));
		}
        return $this->render('ScubeAdminSystemBundle:AdminSystem:dbsettings.html.twig', array('form' => $form->createView()));
    }
	
	public function mtnsettingsAction()
    {
		$users = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:User')->findAll();

		$allowedUsersList = array();
		
		foreach ($users as $userAllowed)
		{
			if ($userAllowed->getPermissionsGroup()->getName() == "administrator" && $userAllowed->getIp() != NULL)
				array_push($allowedUsersList, $userAllowed);
		}
        return $this->render('ScubeAdminSystemBundle:AdminSystem:mtnsettings.html.twig', array('allowedUsersList' => $allowedUsersList));
    }
	
	public function modifyAllowedUsersAction()
	{
		
	}
	
	private function getCurMode()
	{
		$file = $this->container->get('kernel')->getRootDir()."/maintenance/mtn.scb";
		
		if (file_exists($file))
		{
			if (filesize($file) >= 1)
				return ("Maintenance");
			else
				return ("Online");
		}
		else
			return ("Unknown");
	}
}

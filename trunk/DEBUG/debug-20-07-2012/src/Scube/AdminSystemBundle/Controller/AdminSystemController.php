<?php

namespace Scube\AdminSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Scube\CoreBundle\Controller\CoreController;
use Scube\BaseBundle\Entity\DbSettings;


class AdminSystemController extends CoreController
{
	// Module Index + Possibility to switch on/off maintenance mode
    public function indexAction()
    {
		$this->preprocessApplication();
		
		$cur_mode = $this->getCurMode();
		if ($cur_mode == "Unknown")
			return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode, 'globalerror' => true));
        return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode));
		
    }
	
	// Controller to switch to online to offline mode and vice-versa
	public function switchModeAction($whichmode)
	{
		$this->preprocessApplication();
	
		$cur_mode = $this->getCurMode();
		
		
		if ($whichmode == "online")
		{
			if ($cur_mode == "Online")
				return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode, 'alreadyOnline' => true));
			else
			{
				if (!$this->changeModeFile("online"));
					return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode, 'globalerror' => true));
				return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode, 'passedOnline' => true));
			}
		}
		else
		{
			if ($cur_mode == "Maintenance")
				return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode, 'alreadyOffline' => true));
			else
			{
				if (!$this->changeModeFile("offline"));
					return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode, 'globalerror' => true));
				return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode, 'passedOffline' => true));
			}
		}
		
		
		if ($cur_mode == "Unknown")
			return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', array('mode' => $cur_mode, 'globalerror' => true));
	}
	
	// Function to modifiy mtn.scb file to switch Online / Offline
	private function changeModeFile($whichmode)
	{
	
		$file = $this->container->get('kernel')->getRootDir()."/maintenance/mtn.scb";

		
		if (!$fd = fopen($file, "r+"))
			return false;
		
		if ($whichmode == "online")
		{
			if (!ftruncate($fd, 0))
			{
				fclose($fd);
				return false;
			}
			else
			{
				fclose($fd);
				return true;
			}
		}
		else
		{
			if (!fwrite($fd, "1"))
			{
				fclose($fd);
		
				return false;
			}
			else
			{
				fclose($fd);
					
				return true;
			}
		}
	}
	
	// Page to modifiy Database settings
	public function dbsettingsAction()
    {
	
		$this->preprocessApplication();
	
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
	
	
	// Page that shows who have permissions for Maintenance (possibility to delete them as well)
	public function mtnsettingsAction()
    {
	
		$this->preprocessApplication();
	
		$allowedUsersList = $this->getAllowedUsers();
		
        return $this->render('ScubeAdminSystemBundle:AdminSystem:mtnsettings.html.twig', array('allowedUsersList' => $allowedUsersList));
    }
	
	// Page that show list of non allowed users for Maintenance (possibility to grant their access to it)
	public function grantAccessAction()
	{
		$this->preprocessApplication();
	
		$deniedUsersList = $this->getDeniedUsers();
		
        return $this->render('ScubeAdminSystemBundle:AdminSystem:grantaccess.html.twig', array('deniedUsersList' => $deniedUsersList));
	}
	
	// Action that push in database modifications on user maintenance permissions.
	public function modifyAllowedUserAction($mode, $id)
	{	
	
		$this->preprocessApplication();
		
		if ($mode == "delete")
		{
			$user_to_delete = $this->getDoctrine()
									->getRepository('ScubeBaseBundle:User')->find($id);
									
			$user_to_delete->setMtnToken(false);
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($user_to_delete);
			$em->flush();
									
			return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_mtnSettings'));
		}
		else
		{
			$user_to_grant = $this->getDoctrine()
									->getRepository('ScubeBaseBundle:User')->find($id);
															
			$user_to_grant->setMtnToken(true);
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($user_to_grant);
			$em->flush();
									
			return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_mtnSettings'));
		}
	}
	
	// Function to clean Logs according to current mode
	public function cleanAction($mode)
	{
		if ($mode == "PROD")
		{
			if (file_exists($this->container->get('kernel')->getRootDir()."/logs/prod.log"))
			{
				$handle = fopen($this->container->get('kernel')->getRootDir()."/logs/prod.log", 'r+');
				ftruncate($handle, 0);
				fclose($handle);
				return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_homepage'));
			}
			else
				return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_homepage'));
		}
		else
		{
			if (file_exists($this->container->get('kernel')->getRootDir()."/logs/dev.log"))
			{
				$handle = fopen($this->container->get('kernel')->getRootDir()."/logs/dev.log", 'r+');
				ftruncate($handle, 0);
				fclose($handle);
				return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_homepage'));
			}
			else
				return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_homepage'));
		}
	}
	
	// Controller to show Prod Logs
	public function prodAction()
	{
		if ($tab = $this->getFullLog("/logs/prod.log"))
		{
			$arr = $this->formatLogArray($tab);
			return $this->render('ScubeAdminSystemBundle:AdminSystem:prod_logs.html.twig', array('logsFull' => $arr, 'error' => false, 'mode' => "PROD"));
		}
		else
			return $this->render('ScubeAdminSystemBundle:AdminSystem:prod_logs.html.twig', array('error' => true, 'mode' => "PROD"));
	}
	
	// Controller to show Dev Logs
	public function devAction()
	{
		if ($tab = $this->getFullLog("/logs/dev.log"))
		{
			$arr = $this->formatLogArray($tab);
			return $this->render('ScubeAdminSystemBundle:AdminSystem:dev_logs.html.twig', array('logsFull' => $arr, 'error' => false, 'mode' => "DEV"));
		}
		else
			return $this->render('ScubeAdminSystemBundle:AdminSystem:dev_logs.html.twig', array('error' => true, 'mode' => "DEV"));
	}
	
	// Function to create proper Array with Logs Messages and infos
	private function formatLogArray($tabLogs)
	{
		$tab = array();
		
		for ($i = 0; !empty($tabLogs[$i]) ; $i++)
		{
			$tab[$i]['id'] = $i;
			$tab[$i]['date'] = $this->getLogDate($tabLogs[$i]);
			$tab[$i]['type'] = $this->getLogErrorType($tabLogs[$i]);
			$tab[$i]['msg'] = $this->getLogMessage($tabLogs[$i]);
		}
		return $tab;
	}
	
	// Function to get Log error Message
	private function getLogMessage($line)
	{
		$tmp = explode(": ", $line);
		$pos = count($tmp) - 1;
		$message = $tmp[$pos];
		return $message;
	}
	
	// Function to get Log error Date
	private function getLogDate($line)
	{
		preg_match("#(\[[^\]]*\])#", $line, $found);
		$found[0] = trim($found[0], "[");
		$found[0] = trim($found[0], "]");
		$date = $found[0];
		return $date;
	}
	
	// Function to get Log Error Type
	private function getLogErrorType($line)
	{
		$tmp = explode(":", $line);
		$errorType = $tmp[3];
		if (strpos($errorType, "\\"))
		{
			$tmp = explode("\\", $errorType);
			$pos = count($tmp) - 1;
			$errorType = $tmp[$pos];
		}
		return $errorType;
	}
	
	// Function to get full Logs
	private function getFullLog($logFile)
	{
		$tab_prev = array();
		
		if (file_exists($this->container->get('kernel')->getRootDir().$logFile))
		{
			$log = $this->container->get('kernel')->getRootDir().$logFile;
			if ($file = fopen($log, "r"))
			{
				$i = 0;
				while ($line = fgets($file))
				{
					if (preg_match("/CRITICAL/", $line) == 1)
					{
						$tab_prev[$i] = $line;
						$i++;
					}
				}
			}
			else
				return false;
			fclose($file);
			return $tab_prev;
		}
		else
			return false;
	}

	// Function to get Allowed Users Maintenance list
	private function getAllowedUsers()
	{
		$users = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:User')->findAll();

		$allowedUsersList = array();
		
		foreach ($users as $userAllowed)
		{
			if ($userAllowed->getMtnToken())
				array_push($allowedUsersList, $userAllowed);
		}
		
		return $allowedUsersList;
	}
	
	
	// Function to get Denied users maintenance list
	private function getDeniedUsers()
	{
		$users = $this->getDoctrine()
						->getRepository('ScubeBaseBundle:User')->findAll();

		$deniedUsersList = array();
		
		foreach ($users as $userDenied)
		{
			if (!$userDenied->getMtnToken())
				array_push($deniedUsersList, $userDenied);
		}
		
		return $deniedUsersList;
	}
	
	// Get Site current mode (Online / Offline)
	private function getCurMode()
	{
		$file = $this->container->get('kernel')->getRootDir()."/maintenance/mtn.scb";
		
		if (file_exists($file))
		{
			if (filesize($file) >= 1)
				return("Maintenance");
			else
				return("Online");
		}
		else
			return("Unknown");
	}
}

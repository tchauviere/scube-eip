<?php

namespace Scube\AdminSystemBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Scube\CoreBundle\Controller\CoreController;

class AdminSystemController extends CoreController
{
	/*
	 * Application Overview
	 */
    public function indexAction()
    {
		$this->preprocessApplication();
		
		$parameters = array();
		$parameters['infos'] = array();
		$parameters['infos']['environment'] = $this->container->get('kernel')->getEnvironment();
		$parameters['infos']['users'] = count($this->getDoctrine()->getRepository('ScubeBaseBundle:User')->findAll());
		$parameters['infos']['apps'] = count($this->getDoctrine()->getRepository('ScubeBaseBundle:Application')->findAll());
		$parameters['infos']['widgets'] = count($this->getDoctrine()->getRepository('ScubeBaseBundle:Widget')->findAll());

        return $this->render('ScubeAdminSystemBundle:AdminSystem:index.html.twig', $parameters);
		
    }

    /*
	 * Application Maintenance
	 */
    public function maintenanceAction()
    {
		$this->preprocessApplication();
		
		/* Get list of authorized / unauthorized users */
		$parameters = array();
		$parameters['authorized_users'] = array();
		$parameters['unauthorized_users'] = array();

		$users = $this->getDoctrine()->getRepository('ScubeBaseBundle:User')->findAll();
		foreach ($users as $u) {
			if ($u->getMaintenancePermission()) $parameters['authorized_users'][] = $u;
			else $parameters['unauthorized_users'][] = $u;
		}

		/* Get status of maintenance mode */
		$file = $this->container->get('kernel')->getRootDir()."/maintenance/mtn.scb";

		if (!file_exists($file))
    		file_put_contents($file, '');

    	if (filesize($file) >= 1)
    		$parameters['maintenance_status'] = true;
    	else
			$parameters['maintenance_status'] = false;
		

        return $this->render('ScubeAdminSystemBundle:AdminSystem:maintenance.html.twig', $parameters);
    }

    /*
	 * Application Maintenance -> Grant user
	 */
    public function maintenanceGrantAction(Request $request, $id)
    {
    	$this->preprocessApplication();

		$user = $this->getDoctrine()->getRepository('ScubeBaseBundle:User')->find($id);

		if (!$user) {
			throw $this->createNotFoundException('No user found for id '.$id);
		}

		$em = $this->getDoctrine()->getEntityManager();
		$user->setMaintenancePermission(true);
		$em->flush();

		return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_maintenance'));
    }

    /*
	 * Application Maintenance -> Ungrant user
	 */
    public function maintenanceUngrantAction(Request $request, $id)
    {
    	$this->preprocessApplication();

		$user = $this->getDoctrine()->getRepository('ScubeBaseBundle:User')->find($id);

		if (!$user) {
			throw $this->createNotFoundException('No user found for id '.$id);
		}

		$em = $this->getDoctrine()->getEntityManager();
		$user->setMaintenancePermission(false);
		$em->flush();

		return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_maintenance'));
    }

    /*
	 * Application Maintenance -> Ungrant user
	 */
    public function maintenanceSwitchActivationAction(Request $request)
    {
    	$this->preprocessApplication();

    	$file = $this->container->get('kernel')->getRootDir()."/maintenance/mtn.scb";

    	if (!file_exists($file))
    		file_put_contents($file, "");

    	if (filesize($file) >= 1)
    		file_put_contents($file, "");
    	else
			file_put_contents($file, "1");

		return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_maintenance'));
    }

    /*
	 * Application Reports
	 */
	public function reportsAction()
	{
		$this->preprocessApplication();

		$logs = $this->getFullLog();
		if ($logs === false)
			throw new \Exception('The cache file is unreadable.');

		$environment_names = array(
			'dev' => $this->get('translator')->trans('Development'),
			'prod' => $this->get('translator')->trans('Production'),
		);

		$parameters = array();
		$parameters['reports'] = $this->formatLogArray($logs);
		$parameters['environment'] = $this->container->get('kernel')->getEnvironment();
		$parameters['environment_readable'] = $environment_names[$parameters['environment']];

		return $this->render('ScubeAdminSystemBundle:AdminSystem:reports.html.twig', $parameters);
	}

	/*
	 * Application Reports Clearing
	 */
	public function reportsClearAction()
	{
		$this->preprocessApplication();

		$log_filename = $this->container->get('kernel')->getRootDir().'/logs/'.$this->container->get('kernel')->getEnvironment().'.log';

		if (file_exists($log_filename))
		{
			file_put_contents($log_filename, '');
		}

		return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_reports'));
	}

	/*
	 * Application Database list
	 */
	public function databaseAction(Request $request)
	{
		$this->preprocessApplication();
	
		$database = $this->parametersToArray();

		$formBuilder = $this->createFormBuilder($database);
		
		$formBuilder->add('database_driver',    'choice', array('choices' => array(
																	'pdo_mysql' => 'MySql (PDO)', 
																	'pdo_sqlite' => 'SQLite (PDO)',
																	'pdo_pgsql' => 'PostgreSQL (PDO)',
																	'pdo_oci' => 'Oracle (PDO)',
																	'pdo_ibm' => 'IBM DB2 (PDO)',
																	'pdo_sqlsrv' => 'SQLServer (PDO)',
																	'ibm_db2' => 'IBM DB2 (native)',
																	'oci8' => 'Oracle (Native)',
																)))
					->add('database_host',   	'text')
					->add('database_name', 		'text')
					->add('database_user', 		'text')
					->add('database_password',  'text', 	array('required'  => false))
					->add('database_port',  	'text', 	array('required'  => false));
		
		$form = $formBuilder->getForm();

		if ($request->getMethod() == 'POST')
		{
			$form->bindRequest($request);
			if ($form->isValid())
			{
				$alter_database = $form->getData();

				$this->replacementIntoParameters($alter_database);
				

				return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_database'));
			}
			else
				return $this->render('ScubeAdminSystemBundle:AdminSystem:database.html.twig', array('form' => $form->createView(), 'error' => true));
		}
        return $this->render('ScubeAdminSystemBundle:AdminSystem:database.html.twig', array('form' => $form->createView()));
	}

	/*
	 * Application Cache
	 */
	public function cacheAction()
	{
		$this->preprocessApplication();

		return $this->render('ScubeAdminSystemBundle:AdminSystem:cache.html.twig');
	}

	/*
	 * Application Cache Clearing
	 */
	public function cacheClearAction()
	{
		$this->preprocessApplication();

		$cache_folder = $this->container->get('kernel')->getRootDir().'/cache/'.$this->container->get('kernel')->getEnvironment();

		if (is_dir($cache_folder))
		{
			$this->rrmdir($cache_folder);
		}

		return $this->redirect($this->generateUrl('ScubeAdminSystemBundle_cache'));
	}






	/*
	 * Convert parameters.ini into array
	 */
	private function parametersToArray()
	{
		$parameters_content = file_get_contents($this->container->get('kernel')->getRootDir().'/config/parameters.ini');
		$parameters_lines = explode("\n", $parameters_content);

		$pattern = '/(.*)=\"(.*)\"/';
		$matches = array();
		$parameters = array();

		foreach ($parameters_lines as $line) {
			preg_match($pattern, $line, $matches);
			if (isset($matches[1]))
				$parameters[$matches[1]] = $matches[2];
		}

		return $parameters;
	}
	/*
	 * Replace values into parameters.ini
	 */
	private function replacementIntoParameters($parameters)
	{
		$parameters_filename = $this->container->get('kernel')->getRootDir().'/config/parameters.ini';
		$parameters_content = file_get_contents($parameters_filename);
		$parameters_lines = explode("\n", $parameters_content);

		$pattern = '/(.*)=\"(.*)\"/';
		$matches = array();

		foreach ($parameters_lines as &$line) {
			preg_match($pattern, $line, $matches);
			if (isset($matches[1]) && isset($parameters[$matches[1]])) {
				$replacement = '${1}="'.$parameters[$matches[1]].'"';
				$line = preg_replace($pattern, $replacement, $line);
			}
		}

		$new_parameters_content = implode("\n", $parameters_lines);

		file_put_contents($parameters_filename, $new_parameters_content);
	}
	/*
	 * Get the current log file content
	 */
	private function getFullLog()
	{
		$tab_prev = array();
		$log_filename = $this->container->get('kernel')->getRootDir().'/logs/'.$this->container->get('kernel')->getEnvironment().'.log';
		if (file_exists($log_filename))
		{
			if ($file = fopen($log_filename, "r"))
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
	/*
	 * Format the log array
	 */
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
	
	/*
	 * Format log message
	 */
	private function getLogMessage($line)
	{
		$tmp = explode(": ", $line);
		$pos = count($tmp) - 1;
		$message = $tmp[$pos];
		return $message;
	}
	
	/*
	 * Format the log date
	 */
	private function getLogDate($line)
	{
		preg_match("#(\[[^\]]*\])#", $line, $found);
		$found[0] = trim($found[0], "[");
		$found[0] = trim($found[0], "]");
		$date = $found[0];
		return $date;
	}
	
	/*
	 * Format the log error type
	 */
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
	/*
	 * Remove a directory and elements inside
	 */
	private function rrmdir($dir) 
	{
		if (!is_dir($dir)) {
			unlink($dir);
			return;
		}
		
		foreach(array_merge(glob($dir . '/*'), glob($dir . '/.svn')) as $file) {
			if(is_dir($file))
				$this->rrmdir($file);
			else
				unlink($file);
		}
		rmdir($dir);
	}
}

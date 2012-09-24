<?php

namespace Scube\AdminLogsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AdminLogsController extends Controller
{
    public function indexAction()
    {
		if ($tab = $this->getPrevLog("/logs/prod.log"))
		{
			$arr = $this->formatLogArray($tab);
			return $this->render('ScubeAdminLogsBundle:AdminLogs:index.html.twig', array('logsPrev' => $tab, 'error' => false, 'mode' => "PROD"));
		}
		else if ($tab = $this->getPrevLog("/logs/dev.log"))
		{
			$arr = $this->formatLogArray($tab);
			return $this->render('ScubeAdminLogsBundle:AdminLogs:index.html.twig', array('logsPrev' => $arr, 'error' => false, 'mode' => "DEV"));
		}
		else
			return $this->render('ScubeAdminLogsBundle:AdminLogs:index.html.twig', array('error' => true, 'mode' => "PROD"));
	
	}
	
	public function cleanAction($mode)
	{
		if ($mode == "PROD")
		{
			if (file_exists($this->container->get('kernel')->getRootDir()."/logs/prod.log"))
			{
				$handle = fopen($this->container->get('kernel')->getRootDir()."/logs/prod.log", 'r+');
				ftruncate($handle, 0);
				fclose($handle);
				return $this->redirect($this->generateUrl('AdminLogsBundle_homepage'));
			}
			else
				return $this->redirect($this->generateUrl('AdminLogsBundle_homepage'));
		}
		else
		{
			if (file_exists($this->container->get('kernel')->getRootDir()."/logs/dev.log"))
			{
				$handle = fopen($this->container->get('kernel')->getRootDir()."/logs/dev.log", 'r+');
				ftruncate($handle, 0);
				fclose($handle);
				return $this->redirect($this->generateUrl('AdminLogsBundle_homepage'));
			}
			else
				return $this->redirect($this->generateUrl('AdminLogsBundle_homepage'));
		}
	}
	
	public function prodAction()
	{
		if ($tab = $this->getFullLog("/logs/prod.log"))
		{
			$arr = $this->formatLogArray($tab);
			return $this->render('ScubeAdminLogsBundle:AdminLogs:prod_logs.html.twig', array('logsFull' => $arr, 'error' => false, 'mode' => "PROD"));
		}
		else
			return $this->render('ScubeAdminLogsBundle:AdminLogs:prod_logs.html.twig', array('error' => true, 'mode' => "PROD"));
	}
	
	public function devAction()
	{
		if ($tab = $this->getFullLog("/logs/dev.log"))
		{
			$arr = $this->formatLogArray($tab);
			return $this->render('ScubeAdminLogsBundle:AdminLogs:dev_logs.html.twig', array('logsFull' => $arr, 'error' => false, 'mode' => "DEV"));
		}
		else
			return $this->render('ScubeAdminLogsBundle:AdminLogs:dev_logs.html.twig', array('error' => true, 'mode' => "DEV"));
	}
	
	private function formatLogArray($tabLogs)
	{
		$tab = array();
		
		for ($i = 0; !empty($tabLogs[$i]) ; $i++)
		{
			$tab[$i]['date'] = $this->getLogDate($tabLogs[$i]);
			$tab[$i]['type'] = $this->getLogErrorType($tabLogs[$i]);
			$tab[$i]['msg'] = $this->getLogMessage($tabLogs[$i]);
		}
		return $tab;
	}
	
	private function getLogMessage($line)
	{
		$tmp = explode(": ", $line);
		$pos = count($tmp) - 1;
		$message = $tmp[$pos];
		return $message;
	}
	
	private function getLogDate($line)
	{
		preg_match("#(\[[^\]]*\])#", $line, $found);
		$found[0] = trim($found[0], "[");
		$found[0] = trim($found[0], "]");
		$date = $found[0];
		return $date;
	}
	
	private function getLogErrorType($line)
	{
		$tmp = explode(":", $line);
		$errorType = $tmp[3];
		return $errorType;
	}
	
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
	
	private function getPrevLog($logFile)
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
					if (preg_match("/CRITICAL/", $line) == 1 && $i <= 2)
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
}

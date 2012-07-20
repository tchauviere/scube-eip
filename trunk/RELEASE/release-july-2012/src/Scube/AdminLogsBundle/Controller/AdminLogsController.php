<?php

namespace Scube\AdminLogsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AdminLogsController extends Controller
{
    public function indexAction()
    {
		$log = $this->container->get('kernel')->getRootDir()."/logs/dev.log";
		if (file_exists($log))
		{
			if ($txtFull = file_get_contents($log))
			{
				$lines = explode("\n", $txtFull);
				return $this->render('ScubeAdminLogsBundle:AdminLogs:index.html.twig', array('devlogs'  => $lines));
			}
			else
				return $this->render('ScubeAdminLogsBundle:AdminLogs:index.html.twig');
		}
		else
			return $this->render('ScubeAdminLogsBundle:AdminLogs:index.html.twig');

	}
}

<?php

namespace Scube\AdminHelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\Application;
use Scube\CoreBundle\Controller\CoreController;


class AdminHelpController extends CoreController
{
    public function indexAction(Request $request)
    {
		$this->preprocessApplication();
		$em = $this->getDoctrine()->getEntityManager();
		$app_list = $this->getMenu();
        return $this->render('ScubeAdminHelpBundle:AdminHelp:index.html.twig', array('app_list'=>$app_list));
    }
	
	public function getHelpAction(Request $request, $id)
	{
		$this->preprocessApplication();
		$em = $this->getDoctrine()->getEntityManager();
		$app_list = $this->getMenu();

		if (file_exists($this->get('kernel')->getRootDir()."/../src/Scube/" . $id ."/Resources/views/". substr($id, 0, strlen($id) - strlen("Bundle")) . "/help.html.twig"))
		{
				$helpPath = "Scube" . $id . ":" . substr($id, 0, strlen($id) - strlen("Bundle")) . ":help.html.twig";
		}
		else
			$helpPath = "ScubeAdminHelpBundle:AdminHelp:noHelp.html.twig";
        return $this->render('ScubeAdminHelpBundle:AdminHelp:displayHelp.html.twig', array('helpFile'=>$helpPath, 'app_list'=>$app_list));
	}
	
	private function getMenu()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Application a ORDER BY a.type, a.name ASC");
		$app_list = $query->getResult();
		return 	$app_list;
	}
}

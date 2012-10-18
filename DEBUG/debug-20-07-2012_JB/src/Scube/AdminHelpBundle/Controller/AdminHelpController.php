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
		
		$t = $this->get('translator')->trans('Welcome in the help module for administrator');
        return $this->render('ScubeAdminHelpBundle:AdminHelp:index.html.twig', array('app_list'=>$app_list, 'welcome'=>$t));
    }
	
	public function getHelpAction(Request $request, $id)
	{
		$this->preprocessApplication();
		$em = $this->getDoctrine()->getEntityManager();
		$app_list = $this->getMenu();
		$default_locale = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "default_locale"));
		$lang = $this->user->getLocale();

		if (file_exists($this->get('kernel')->getRootDir()."/../src/Scube/" . $id ."/Resources/views/". substr($id, 0, strlen($id) - strlen("Bundle")) . "/help_". $lang  .".html.twig"))
		{
				$helpPath = "Scube" . $id . ":" . substr($id, 0, strlen($id) - strlen("Bundle")) . ":help_". $lang  .".html.twig";
		}
		else if (file_exists($this->get('kernel')->getRootDir()."/../src/Scube/" . $id ."/Resources/views/". substr($id, 0, strlen($id) - strlen("Bundle")) . "/help_". $default_locale->getValue()  .".html.twig"))
		{
				$helpPath = "Scube" . $id . ":" . substr($id, 0, strlen($id) - strlen("Bundle")) . ":help_". $default_locale->getValue()  .".html.twig";
		}
		else
			$helpPath = "ScubeAdminHelpBundle:AdminHelp:noHelp.html.twig";
		$id = substr($id, 0, strlen($id) - strlen("Bundle"));
        return $this->render('ScubeAdminHelpBundle:AdminHelp:displayHelp.html.twig', array('id'=>$id, 'helpFile'=>$helpPath, 'app_list'=>$app_list));
	}
	
	private function getMenu()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Application a ORDER BY a.type, a.name ASC");
		$app_list = $query->getResult();
		return 	$app_list;
	}
}

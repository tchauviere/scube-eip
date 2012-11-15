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
		
        return $this->render('ScubeAdminHelpBundle:AdminHelp:index.html.twig', array('app_list'=>$this->getMenu()));
    }
	
	public function getHelpAction(Request $request, $id)
	{
		$this->preprocessApplication();
		$current_app = $this->getDoctrine()->getRepository('ScubeBaseBundle:Application')->find($id);
		$default_locale = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "default_locale"));
		$lang = $this->user->getLocale();
		
		$bundle_short_name = substr($current_app->getBundleName(), 0, strlen($current_app->getBundleName()) - strlen("Bundle"));
		$views_directory = $this->get('kernel')->getRootDir()."/../src/Scube/".$current_app->getBundleName()."/Resources/views/";

		if (file_exists($views_directory . $bundle_short_name . "/help_". $lang  .".html.twig"))
			$help_path = "Scube" . $current_app->getBundleName() . ":" . $bundle_short_name . ":help_". $lang  .".html.twig";
		else if (file_exists($views_directory . $bundle_short_name . "/help_". $default_locale->getValue()  .".html.twig"))
			$help_path = "Scube" . $current_app->getBundleName() . ":" . $bundle_short_name . ":help_". $default_locale->getValue()  .".html.twig";
		else
			$help_path = "ScubeAdminHelpBundle:AdminHelp:noHelp.html.twig";
		
        return $this->render('ScubeAdminHelpBundle:AdminHelp:displayHelp.html.twig', array('app_list'=>$this->getMenu(), 'help_app'=>$current_app, 'help_view'=>$help_path));
	}
	
	private function getMenu()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Application a ORDER BY a.type, a.name ASC");
		$app_list = $query->getResult();
		return 	$app_list;
	}
}

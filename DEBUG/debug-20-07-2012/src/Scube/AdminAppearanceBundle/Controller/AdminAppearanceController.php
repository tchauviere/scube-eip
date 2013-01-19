<?php

namespace Scube\AdminAppearanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminAppearanceController extends Controller
{
	public function indexAction(Request $request, $theme=false)
    {
    	$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting');
		$current = $repository->findOneBy(array('key' => "theme"))->getValue();

    	if (!$theme)
    		return $this->redirect($this->generateUrl('ScubeAdminAppearanceBundle_theme', array('theme'=>$current)));

    	$list = array();
    	foreach (glob($this->get('kernel')->getRootDir()."/../web/themes/*") as $file) {
    		if (basename($file) != 'current')
    		$list[] = basename($file);
    	}
		
		return $this->render('ScubeAdminAppearanceBundle:AdminAppearance:index.html.twig', array("list"=>$list, 'current'=>$current, 'view' => $theme));
    }
    public function setCurrentAction(Request $request, $theme)
    {
		$files = glob($this->get('kernel')->getRootDir()."/../web/themes/".$theme."/*");
		
		foreach ($files as $f)
		{
			copy($f, $this->get('kernel')->getRootDir()."/../web/themes/current/".basename($f));
		}

		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting');
		$current = $repository->findOneBy(array('key' => "theme"));
		$current->setValue($theme);
		$em = $this->getDoctrine()->getEntityManager();
	   	$em->flush();
		
		return $this->redirect($this->generateUrl('ScubeAdminAppearanceBundle_theme', array('theme'=>$current->getValue())));
    }
}

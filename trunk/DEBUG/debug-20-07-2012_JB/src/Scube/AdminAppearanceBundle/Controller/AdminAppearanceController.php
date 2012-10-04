<?php

namespace Scube\AdminAppearanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminAppearanceController extends Controller
{
	public function indexAction(Request $request)
    {
		$draft_pending = false;
		
		if (count(glob($this->get('kernel')->getRootDir()."/../web/themes/draft/*")))
			$draft_pending = true;
		
		return $this->render('ScubeAdminAppearanceBundle:AdminAppearance:index.html.twig', array("draft_pending"=>$draft_pending));
    }
	public function setOnlineAction(Request $request)
    {
		$files = glob($this->get('kernel')->getRootDir()."/../web/themes/draft/*");
		
		foreach ($files as $f)
		{
			rename($f, $this->get('kernel')->getRootDir()."/../web/themes/current/".basename($f));
		}
		
		return $this->redirect($this->generateUrl('ScubeAdminAppearanceBundle_homepage'));
    }
	public function setDefaultAction(Request $request)
    {
		$files = glob($this->get('kernel')->getRootDir()."/../web/themes/default/*");
		
		foreach ($files as $f)
		{
			copy($f, $this->get('kernel')->getRootDir()."/../web/themes/current/".basename($f));
		}
		
		return $this->redirect($this->generateUrl('ScubeAdminAppearanceBundle_homepage'));
    }
    public function generalAction(Request $request)
    {
		$defaultData = array();
		$form = $this->createFormBuilder($defaultData)
			->add('BODY_BGCOLOR', 'text')
			->add('H1_COLOR', 'text')
			->getForm();
	
			if ($request->getMethod() == 'POST') {
				$form->bindRequest($request);
	
				$data = $form->getData();
				$css_model = file_get_contents($this->get('kernel')->getRootDir()."/../web/themes/model/general.css");
				
				$css_model = str_replace("[#BODY_BGCOLOR#]", $data["BODY_BGCOLOR"], $css_model);
				$css_model = str_replace("[#H1_COLOR#]", $data["H1_COLOR"], $css_model);
				
				file_put_contents($this->get('kernel')->getRootDir()."/../web/themes/draft/general.css", $css_model);
			}
		
		
        return $this->render('ScubeAdminAppearanceBundle:AdminAppearance:general.html.twig', array("form"=>$form->createView()));
    }
}

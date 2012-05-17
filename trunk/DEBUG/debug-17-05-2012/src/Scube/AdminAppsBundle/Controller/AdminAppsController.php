<?php

namespace Scube\AdminAppsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\Users;

class AdminAppsController extends Controller
{
    
    public function indexAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Application a ORDER BY a.name ASC");
		$app_list = $query->getResult();
		return $this->render('ScubeAdminAppsBundle:AdminApps:index.html.twig', array('app_list'=>$app_list));
    }
	
	public function activateAction(Request $request, $id, $val)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$application = $em->getRepository('ScubeBaseBundle:Application')->find($id);
	
		if (!$application) {
			throw $this->createNotFoundException('No application found for id '.$id);
		}
		
		if ($application->getNecessary() == false)
		{
			$application->setActivated($val);
		}
		$em->flush();
		return $this->redirect($this->generateUrl('AdminAppsBundle_homepage'));
    }
	
	public function removeAction(Request $request, $id)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$application = $em->getRepository('ScubeBaseBundle:Application')->find($id);
	
		if (!$application) {
			throw $this->createNotFoundException('No application found for id '.$id);
		}
		
		if ($application->getNecessary() == false)
		{
			$em->remove($application);
		}
		$em->flush();
		return $this->redirect($this->generateUrl('AdminAppsBundle_homepage'));
    }
	
	public function installAction(Request $request)
    {
		$defaultData = array();
		$form = $this->createFormBuilder($defaultData)
			->add('zipped_application', 'file')
			->getForm();
	
			if ($request->getMethod() == 'POST') {
				$form->bindRequest($request);
	
				$data = $form->getData();
				//$form['zipped_application']->getData()->move($this->get('kernel')->getRootDir()."/../src/Scube/", "lol");
			}
		return $this->render('ScubeAdminAppsBundle:AdminApps:install.html.twig', array('form' => $form->createView(), 'success'=>false));
    }
}

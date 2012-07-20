<?php

namespace Scube\AdminSettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Scube\BaseBundle\Entity\ScubeSetting;


class AdminSettingsController extends Controller
{
    
    public function indexAction()
    {
       $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery("SELECT u FROM ScubeBaseBundle:ScubeSetting u ORDER BY u.key ASC");
                $settings_list = $query->getResult();
                return $this->render('ScubeAdminSettingsBundle:AdminSettings:index.html.twig', array('settings_list'=>$settings_list));
    }

    public function editAction(Request $request, $id)
    {
	$setting = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->find($id);

	if (!$setting) {
          throw $this->createNotFoundException('No setting found for id '.$id);
        }

	$form = $this->createFormBuilder($setting)
           ->add('Key', 'text')
           ->add('Value', 'text')
           ->getForm();
               
               if ($request->getMethod() == 'POST') {
                       $form->bindRequest($request);
       
                       if ($form->isValid()) {
                       
                               $em = $this->getDoctrine()->getEntityManager();
                               $em->flush();
                               
                               return $this->render('ScubeAdminSettingsBundle:AdminSettings:edit_setting.html.twig', array('setting'=>$setting, 'form' => $form->createView(), "success"=>true));
                       }
               }
                       
               return $this->render('ScubeAdminSettingsBundle:AdminSettings:edit_setting.html.twig', array('setting'=>$setting, 'form' => $form->createView(), "success"=>false));
    }
}

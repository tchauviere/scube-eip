<?php

namespace Scube\AdminSettingsBundle\Controller;

use Scube\CoreBundle\Controller\CoreController;

use Symfony\Component\HttpFoundation\Request;
use Scube\BaseBundle\Entity\ScubeSetting;


class AdminSettingsController extends CoreController
{
    
    public function indexAction()
    {
		  $this->preprocessApplication();
		
      $settings_list = $this->getDoctrine()
				   			  ->getEntityManager()
				   			  ->createQuery("SELECT u FROM ScubeBaseBundle:ScubeSetting u ORDER BY u.key ASC")
				   			  ->getResult();
		  return $this->render('ScubeAdminSettingsBundle:AdminSettings:index.html.twig', array('settings_list'=>$settings_list));
    }

    public function editAction(Request $request, $id)
    {
  		$this->preprocessApplication();

  		$setting = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->find($id);

  		if (!$setting) {
  		  throw $this->createNotFoundException('No setting found for id '.$id);
  		}

  		$form = $this->createFormBuilder($setting)
             			 ->add('Key', 'text', array("read_only"=>true))
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

    public function siteInfosAction(Request $request)
    {
      $this->preprocessApplication();
    
      $site_name = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "site_name"))->getValue();
      $logo = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "logo"))->getValue();

      $defaultData = array('site_name'=> $site_name, 'logo'=>"");

      $form = $this->createFormBuilder($defaultData)
                   ->add('site_name', 'text')
                   ->add('logo', 'file', array("required"=>false))
                   ->getForm();
      

      if ($request->getMethod() == 'POST') {
          $form->bindRequest($request);
          if ($form->isValid()) {   
              $result = $form->getData();
              
              $em = $this->getDoctrine()->getEntityManager();

              $site_name_obj = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "site_name"));
              $logo_obj = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "logo"));

              $site_name_obj->setValue($result['site_name']);
              
              if ($form['logo']->getData()) {
                if (!$form['logo']->getData()->getSize()) {
                  $error = true;
                  $error_msg = "Accepted extensions are jpg, png, gif and bmp.";
                  return $this->render('ScubeAdminSettingsBundle:AdminSettings:site_infos.html.twig', array('form' => $form->createView(), 'logo' => $logo, "success"=>false, 'error'=>$error, 'error_msg'=>$error_msg));
                }

                $authorised_extensions = array('png', 'jpg', 'jpeg', 'gif');
                $extension = strtolower($form['logo']->getData()->guessExtension());

                if (!in_array($extension, $authorised_extensions)) {
                  $error = true;
                  $error_msg = "Accepted extensions are jpg, png, gif and bmp.";
                  return $this->render('ScubeAdminSettingsBundle:AdminSettings:site_infos.html.twig', array('form' => $form->createView(), 'logo' => $logo, "success"=>false, 'error'=>$error, 'error_msg'=>$error_msg));
                }
                //exit($this->get('kernel')->getRootDir()."|".$this->get('request')->getBasePath()."/");
                $final_filename = 'logo.'.$extension;
                
                $form['logo']->getData()->move($this->get('kernel')->getRootDir(). '/../web/', $final_filename);
                $logo_obj->setValue($this->get('request')->getBasePath().'/'.$final_filename);
              }
              
              $em->flush();             
              return $this->render('ScubeAdminSettingsBundle:AdminSettings:site_infos.html.twig', array('form' => $form->createView(), 'logo' => $logo, "success"=>true));
          }
      } 

      return $this->render('ScubeAdminSettingsBundle:AdminSettings:site_infos.html.twig', array('form' => $form->createView(), 'logo' => $logo, "success"=>false));
    }
}

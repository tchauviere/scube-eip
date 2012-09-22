<?php

namespace Scube\MyAppsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;
use Scube\BaseBundle\Entity\BaseInterface;
use Scube\BaseBundle\Entity\Widget;
use Scube\BaseBundle\Entity\InterfaceWidget;

class MyAppsController extends Controller
{
    
    /* Applications */
	public function myAppsAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$app_list = $user->getPermissionsGroup()->getApplications();
		
		return $this->render('ScubeMyAppsBundle:MyApps:my_apps.html.twig', array('user' => $user, 'app_list' => $app_list));
    }
	/* Widgets */
	public function addWidgetAction(Request $request, $default_x=NULL, $default_y=NULL)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		$widget_list = $user->getBaseInterface()->getWidgets();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:Widget');
		$widgets_available = $repository->findAll();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting');
		$dashboard_width = $repository->findOneBy(array('key' => "dashboard_cell_width"));
		$dashboard_height = $repository->findOneBy(array('key' => "dashboard_cell_height"));
		$array_coord = array();
		
		for ($i = 0; $i < $dashboard_height->getValue(); $i++)
		{
			$array_coord[$i] = array();
			for ($j = 0; $j < $dashboard_width->getValue(); $j++)
			{
				$array_coord[$i][$j] = 0;
			}
		}
		foreach ($widget_list as $widget)
		{
			for ($i = 0; $i < $widget->getHeight(); $i++)
			{
				for ($j = 0; $j < $widget->getWidth(); $j++)
				{
					$array_coord[$widget->getPosY() + $i][$widget->getPosX() + $j] = 1;
				}
			}
		}
		
		$widgets_available_list = array();
		foreach ($widgets_available as $wid)
		{
			$widgets_available_list[$wid->getId()] = $wid->getName();
		}
		
		$widget = new InterfaceWidget();
		
		$defaultData = array('widget'=>false, 'pos_x'=>$default_x, 'pos_y'=>$default_y);
		$form = $this->createFormBuilder($defaultData)
            ->add('widget', 'choice', array('choices' => $widgets_available_list))
            ->add('pos_x', 'hidden')
			->add('pos_y', 'hidden')
            ->getForm();
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			
			$datas = $form->getData();
			
			$widget_get = $this->getDoctrine()->getRepository('ScubeBaseBundle:Widget')->find($datas['widget']);
			
			$widget->setWidget($widget_get);
			$widget->setWidth(1);
			$widget->setHeight(1);
			$widget->setPosX($datas['pos_x']);
			$widget->setPosY($datas['pos_y']);
			
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($widget);
			$em->flush();
			
			$user->getBaseInterface()->addInterfaceWidget($widget);
			$em = $this->getDoctrine()->getEntityManager();
			$em->flush();
			
			return $this->render('ScubeMyAppsBundle:MyApps:add_widget.html.twig', array('user' => $user, 'success' => true));
		}
		
		return $this->render('ScubeMyAppsBundle:MyApps:add_widget.html.twig', array('user' => $user, 'widget_list' => $widget_list, 'widgets_available' => $widgets_available, 'dashboard_width'=>$dashboard_width, 'dashboard_height'=>$dashboard_height, 'array_coord'=>$array_coord, 'form'=>$form->createView(), 'success'=>false, 'default_x'=>$default_x, 'default_y'=>$default_y));
		
    }
	
	/* CALLED BY AJAX */
	public function saveWidgetPositionAction(Request $request, $widget_id=false, $pos_x=0, $pos_y=0)
    {
		$widget = $this->getDoctrine()->getRepository('ScubeBaseBundle:InterfaceWidget')->find($widget_id);
		$widget->setPosX($pos_x);
		$widget->setPosY($pos_y);
		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();
		return new Response('');
    }
	public function deleteWidgetAction(Request $request, $id=false)
    {
		
		$widget = $this->getDoctrine()->getRepository('ScubeBaseBundle:InterfaceWidget')->find($id);
		
		if (!$widget) {
			throw $this->createNotFoundException('No widget found for id '.$id);
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($widget);
		$em->flush();
		
		return new Response('');
    }
}

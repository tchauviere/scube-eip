<?php

namespace Widgets\ClockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('WidgetsClockBundle:Default:index.html.twig');
    }
	
	public function clockAction()
    {
        return $this->render('WidgetsClockBundle:Default:clock.html.twig');
    }
}

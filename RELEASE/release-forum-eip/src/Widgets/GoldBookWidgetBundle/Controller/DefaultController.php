<?php

namespace Widgets\GoldBookWidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('WidgetsGoldBookWidgetBundle:Default:index.html.twig');
    }
}

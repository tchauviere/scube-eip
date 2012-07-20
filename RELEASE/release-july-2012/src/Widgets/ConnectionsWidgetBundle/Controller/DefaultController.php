<?php

namespace Widgets\ConnectionsWidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('WidgetsConnectionsWidgetBundle:Default:index.html.twig');
    }
}

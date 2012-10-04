<?php

namespace Widgets\MediasWidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('WidgetsMediasWidgetBundle:Default:index.html.twig');
    }
}

<?php

namespace Widgets\SimpleClockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('WidgetsSimpleClockBundle:Default:index.html.twig');
    }
}

<?php

namespace Apps\TestAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('AppsTestAppBundle:Default:index.html.twig');
    }
}

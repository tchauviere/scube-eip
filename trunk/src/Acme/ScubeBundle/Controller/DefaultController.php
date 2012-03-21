<?php

namespace Acme\ScubeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('AcmeScubeBundle:Default:index.html.twig', array('name' => $name));
    }
}

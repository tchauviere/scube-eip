<?php

namespace Scube\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BaseController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('ScubeBaseBundle:Base:index.html.twig', array('name' => $name));
    }
}

<?php

namespace Scube\AdminHelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AdminHelpController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('ScubeAdminHelpBundle:AdminHelp:index.html.twig', array());
    }
}

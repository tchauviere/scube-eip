<?php

namespace Scube\AdminAppsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminAppsController extends Controller
{
    
    public function indexAction(Request $request)
    {
		return $this->render('ScubeAdminAppsBundle:AdminApps:index.html.twig');
    }
}

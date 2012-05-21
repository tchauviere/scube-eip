<?php

namespace Scube\FriendsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FriendsController extends Controller
{
    
    public function indexAction(Request $request)
    {
		return $this->render('ScubeFriendsBundle:Friends:friends.html.twig');
    }
	public function WidgetAction(Request $request)
    {
		return $this->render('ScubeFriendsBundle:Friends:widget.html.twig');
    }
}

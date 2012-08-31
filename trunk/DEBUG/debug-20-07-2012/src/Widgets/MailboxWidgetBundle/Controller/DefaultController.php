<?php

namespace Widgets\MailboxWidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('WidgetsMailboxWidgetBundle:Default:index.html.twig');
    }
}

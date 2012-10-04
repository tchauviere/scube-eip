<?php

namespace Widgets\CalendarWidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('WidgetsCalendarWidgetBundle:Default:index.html.twig');
    }
}

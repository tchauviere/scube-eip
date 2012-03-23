<?php
// src/Acme/ScubeBundle/Controller/ScubeController.php
namespace Acme\ScubeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScubeController extends Controller
{
    public function indexAction()
    {
	return $this->render('AcmeScubeBundle:Scube:index.html.twig'/*, array('user' => $name)*/);
    }
}
?>
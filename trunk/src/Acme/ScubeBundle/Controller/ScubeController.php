<?php
// src/Acme/ScubeBundle/Controller/ScubeController.php
namespace Acme\ScubeBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class ScubeController
{
    public function indexAction()
    {
        return new Response('<html><body>Hello !</body></html>');
    }
}
?>
<?php

namespace Scube\GoldBookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;
use Scube\CoreBundle\Controller\CoreController;

use Scube\GoldBookBundle\Entity\Comment;
use Scube\GoldBookBundle\Form\CommentType;

class GoldBookController extends CoreController
{
    
    public function indexAction()
    {
        $this->preprocessApplication();
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $comment->setUserId($this->user->getId());
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($form->getData());
                $em->flush(); 
                   
                // Perform some action, such as sending an email

                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('ScubeGoldBookBundle_homepage'));
            }
        }
        return $this->render('ScubeGoldBookBundle:GoldBook:index.html.twig', array('form' => $form->createView()));
    }
    
    public function recordAction()
    {
        return $this->render('ScubeGoldBookBundle:GoldBook:record.html.twig', array('form' => $form->createView()));
    }
}

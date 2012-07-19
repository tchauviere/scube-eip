<?php

namespace Scube\MailboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\Conversation;
use Scube\BaseBundle\Entity\Mail;

class MailboxController extends Controller
{
    
    public function indexAction(Request $request, $id_user=false)
    {
		$session = $this->getRequest()->getSession();
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		if ($id_user)
		{
			$user_to_load = $repository->find($id_user);
			$conversation_to_load = false;
			
			/* CONVERSATION OF CONNECTED USER */
			$conversations = $user->getMailbox()->getConversations();
			foreach ($conversations as $conv)
			{
				if ($conv->getInterlocutor() == $user_to_load)
				{
					$conversation_to_load = $conv;
					break ;
				}
			}
			if (!$conversation_to_load)
			{
				$conversation_to_load = new Conversation();
				$conversation_to_load->setInterlocutor($user_to_load);
				
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($conversation_to_load);
				$user->getMailbox()->addConversation($conversation_to_load);
				$em->flush();
			}
			
			
			
			$new_mail = new Mail();
			$form = $this->createFormBuilder($new_mail)
			   ->add('message', 'textarea')
			   ->getForm();
				   
			   if ($request->getMethod() == 'POST') {
				   
						/* CONVERSATION OF INTERLOCUTOR */
						$conversations_inter = $user_to_load->getMailbox()->getConversations();
						foreach ($conversations_inter as $conv)
						{
							if ($conv->getInterlocutor() == $user)
							{
								$conversation_inter = $conv;
								break ;
							}
						}
						if (!$conversation_inter)
						{
							$conversation_inter = new Conversation();
							$conversation_inter->setInterlocutor($user);
							
							$em = $this->getDoctrine()->getEntityManager();
							$em->persist($conversation_inter);
							$user_to_load->getMailbox()->addConversation($conversation_inter);
							$em->flush();
						}
				   
					   $form->bindRequest($request);
	   
					   if ($form->isValid()) {
						   
						   		$mail_date = new \Datetime();
					   			/* UPDATE CONVERSATION OF CONNECTED USER */
								$new_mail->setType("out");
								$new_mail->setMailingDate($mail_date);
								
							   $em = $this->getDoctrine()->getEntityManager();
							   $em->persist($new_mail);
							   $conversation_to_load->addMail($new_mail);
							   $em->flush();
							   
							   /* UPDATE CONVERSATION OF CONNECTED USER */
							   $new_mail_inter = new Mail();
							   $new_mail_inter->setMessage($new_mail->getMessage());
								$new_mail_inter->setType("in");
								$new_mail_inter->setMailingDate($mail_date);
								
							   $em = $this->getDoctrine()->getEntityManager();
							   $em->persist($new_mail_inter);
							   $conversation_inter->addMail($new_mail_inter);
							   $em->flush();
					   }
			   }
			   
			  /* RE-CREATE THE FORM TO EMPTY THE INPUT */
			$new_mail = new Mail();
			$form = $this->createFormBuilder($new_mail)
			   ->add('message', 'textarea')
			   ->getForm();
			   
			return $this->render('ScubeMailboxBundle:Mailbox:index.html.twig', array('user' => $user, 'conversation' => $conversation_to_load, 'form' => $form->createView()));
		}
        return $this->render('ScubeMailboxBundle:Mailbox:index.html.twig', array('user' => $user, 'conversation' => false));
    }
	
	public function	UsersListAction(Request $request, $search="")
	{
		if (!$search)
			$search = "%";
		else
			$search = "%".str_replace(" ", "%", $search)."%";
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT u FROM ScubeBaseBundle:User u WHERE CONCAT(CONCAT(u.surname, ' '), u.firstname) LIKE :search OR CONCAT(CONCAT(u.firstname, ' '), u.surname) LIKE :search OR u.firstname LIKE :search OR u.surname LIKE :search ORDER BY u.firstname ASC")->setParameters(array('search' => $search));
		$users_list = $query->getResult();
		
		return $this->render('ScubeMailboxBundle:Mailbox:users_list.html.twig', array('users_list'=>$users_list));
	}
}

<?php

namespace Scube\MailboxBundle\Controller;

use Scube\CoreBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\Conversation;
use Scube\BaseBundle\Entity\Mail;

class MailboxController extends CoreController
{
    
    public function indexAction(Request $request, $users_selected=false)
    {
    	$this->preprocessApplication();

		$user = $this->user;
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');

		$all_users = $repository->findAll();
		foreach ($all_users as $k => $u) {
			if ($u->getId() == $user->getId())
				unset($all_users[$k]);
		}
		
		if ($users_selected)
		{
			$users_selected_array = explode('-', $users_selected);

			$conversation_to_load = $this->getConversationByRecipients($user, $users_selected_array);
			
			$new_mail = new Mail();
			$form = $this->createFormBuilder($new_mail)
			   ->add('message', 'textarea')
			   ->getForm();
				   
			   if ($request->getMethod() == 'POST') {
				   
					// CONVERSATION OF RECIPIENTS
			   		$recipients_conversation = array();
			   		foreach ($users_selected_array as $recipient_id) {
			   			$tmp_user = $repository->find($recipient_id);
			   			$tmp_id_list = $users_selected_array;
			   			$tmp_id_list[] = $user->getId();

			   			unset($tmp_id_list[array_search($tmp_user->getId(), $tmp_id_list)]);

			   			$recipients_conversation[] = $this->getConversationByRecipients($tmp_user, $tmp_id_list);
			   		}
			   
				   $form->bindRequest($request);
   
				   if ($form->isValid()) {
					   
				   	   $mail_date = new \Datetime();
			   		   // UPDATE CONVERSATION OF CONNECTED USER
					   $new_mail->setType("out");
					   $new_mail->setMailingDate($mail_date);
					   $new_mail->setAuthor($user);

					   $conversation_to_load->setDateLastMail($mail_date);
						
					   $em = $this->getDoctrine()->getEntityManager();
					   $em->persist($new_mail);
					   $conversation_to_load->addMail($new_mail);
					   $em->flush();
					   
					   // UPDATE CONVERSATION OF RECIPIENTS
					   foreach ($recipients_conversation as $recipient_conv) {
					   	   $new_mail_recipient = new Mail();
						   $new_mail_recipient->setMessage($new_mail->getMessage());
						   $new_mail_recipient->setType("in");
						   $new_mail_recipient->setMailingDate($mail_date);
						   $new_mail_recipient->setAuthor($user);
							
						   $recipient_conv->setDateLastMail($mail_date);
						   $recipient_conv->setNewMails(true);

						   $em = $this->getDoctrine()->getEntityManager();
						   $em->persist($new_mail_recipient);
						   $recipient_conv->addMail($new_mail_recipient);
						   $em->flush();
					   }
				   }
			   }
			   
			// RE-CREATE THE FORM TO EMPTY THE INPUT
			$new_mail = new Mail();
			$form = $this->createFormBuilder($new_mail)
			   ->add('message', 'textarea')
			   ->getForm();
			if (\Scube\BaseBundle\Controller\BaseController::isMobile())
				return $this->render('ScubeBaseBundle:Base_Mobile:conversation.html.twig', array('user' => $user, 'conversation' => $conversation_to_load, 'form' => $form->createView()));
			return $this->render('ScubeMailboxBundle:Mailbox:index.html.twig', array('user' => $user, 'conversation' => $conversation_to_load, 'form' => $form->createView(), 'users_selected' => $users_selected, 'all_users' => $all_users));
		}
		else
		{
			$em = $this->getDoctrine()->getEntityManager();
			$query = $em->createQuery("SELECT u, m, c FROM ScubeBaseBundle:User u JOIN u.mailbox m JOIN m.conversations c WHERE u.id=:id_user ORDER BY c.date_last_mail DESC")->setParameters(array('id_user' => $user->getId()));
			$conv_result = $query->getResult();
			$conversations = array();
			if ($conv_result) {
				$conv_tmp = $query->getResult();
				$conversations = $conv_tmp[0]->getMailbox()->getConversations();
			}
		}

        return $this->render('ScubeMailboxBundle:Mailbox:index.html.twig', array('user' => $user, 'conversation' => false, 'all_users' => $all_users, 'conversations_list' => $conversations));
    }
	
	public function	usersListAction(Request $request, $users_selected, $search="", $keep_extended=false)
	{
		$this->preprocessApplication();

		$time = time();
		$users_selected = explode('-', $users_selected);

		if (!$search)
			$search = "%";
		else
			$search = "%".str_replace(" ", "%", $search)."%";
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT u FROM ScubeBaseBundle:User u WHERE CONCAT(CONCAT(u.surname, ' '), u.firstname) LIKE :search OR CONCAT(CONCAT(u.firstname, ' '), u.surname) LIKE :search OR u.firstname LIKE :search OR u.surname LIKE :search ORDER BY u.firstname ASC")->setParameters(array('search' => $search));
		$users = $query->getResult();

		$online = array();
		$offline = array();
		foreach ($users as $u) {
			/* User is me -> discard */
			if ($u->getId() == $this->user->getId())
				continue;
			if (!$u->getOnline() && in_array($u->getId(), $users_selected))
				$keep_extended = true;

			if ($u->getOnline())
				$online[] = $u;
			else
				$offline[] = $u;
		}

		if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:messages.html.twig', array('users_list'=>$users));

		return $this->render('ScubeMailboxBundle:Mailbox:users_list.html.twig', array('users_online'=>$online, 'users_offline'=>$offline, 'time' => $time, 'keep_extended' => $keep_extended, 'users_selected' => $users_selected));
	}

	public function	mailListAction(Request $request, $users_selected)
	{
		$this->preprocessApplication();

		$time = time();
		$users_selected_array = explode('-', $users_selected);

		$conversation_to_load = $this->getConversationByRecipients($this->user, $users_selected_array);

	    $em = $this->getDoctrine()->getEntityManager();
	   	$conversation_to_load->setNewMails(false);
	    $em->flush();

		return $this->render('ScubeMailboxBundle:Mailbox:mail_list.html.twig', array('user' => $this->user, 'conversation' => $conversation_to_load, 'users_selected' => $users_selected));
	}

	/*
	 * Get a conversation depending on recipients
	 * @param
	 *   - $user: User object you want to get conversation
	 *   - $users_list: array containing id of users (eg: array(1, 2, 3))
	 */
	private function getConversationByRecipients($user, $users_list) {

		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		foreach ($users_list as $usr_selected) {
			$tmp_usr = $repository->find($usr_selected);
			$users_to_load[$tmp_usr->getId()] = $tmp_usr;
		}

		$conversations = $user->getMailbox()->getConversations();
		$conversation_to_load = false;
		foreach ($conversations as $conv)
		{
			$recipients = $conv->getRecipients();
			$id_recipients = array();
			foreach ($recipients as $r) {
				$id_recipients[] = $r->getId();
			}
			$users_to_load_values = array_keys($users_to_load);
			$diff1 = array_diff($users_to_load_values, $id_recipients);
			$diff2 = array_diff($id_recipients, $users_to_load_values);
			if (empty($diff1) && empty($diff2))
			{
				$conversation_to_load = $conv;
				break ;
			}
		}

		if (!$conversation_to_load)
		{
			$conversation_to_load = new Conversation();
			foreach ($users_to_load as $utl) {
				$conversation_to_load->addUser($utl);
			}
			$conversation_to_load->setNewMails(false);
			$conversation_to_load->setDateLastMail(new \Datetime());

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($conversation_to_load);
			$user->getMailbox()->addConversation($conversation_to_load);
			$em->flush();
		}

		return $conversation_to_load;
	}
}

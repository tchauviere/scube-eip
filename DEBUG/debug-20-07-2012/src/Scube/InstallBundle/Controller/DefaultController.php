<?php

namespace Scube\InstallBundle\Controller;

use Scube\BaseBundle\Entity\Application;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;
use Scube\BaseBundle\Entity\BaseInterface;
use Scube\BaseBundle\Entity\PermissionsGroup;
use Scube\BaseBundle\Entity\ScubeSetting;
use Scube\BaseBundle\Entity\Widget;
use Scube\BaseBundle\Entity\InterfaceWidget;
use Scube\BaseBundle\Entity\ConnectionsGroup;
use Scube\BaseBundle\Entity\Calendar;
use Scube\BaseBundle\Entity\Mailbox;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

	public function preprocessAction(Request $request)
	{
		/* Default Applications */
		$default_app = new Application();
		$default_app->setName("Applications");
		$default_app->setBundleName("AdminAppsBundle");
		$default_app->setLink("AdminAppsBundle_homepage");
		$default_app->setType("admin");
		$default_app->setDescription("Manage installed applications on your server and install new applications now !");
		$default_app->setActivated(true);
		$default_app->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app);
		$em->flush();
		
		$default_app2 = new Application();
		$default_app2->setName("Users & Groups");
		$default_app2->setBundleName("AdminUserBundle");
		$default_app2->setLink("AdminUserBundle_homepage");
		$default_app2->setType("admin");
		$default_app2->setDescription("Manage users, groups and permissions.");
		$default_app2->setActivated(true);
		$default_app2->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app2);
		$em->flush();
		
		$default_app3 = new Application();
		$default_app3->setName("Settings");
		$default_app3->setBundleName("AdminSettingsBundle");
		$default_app3->setLink("AdminSettingsBundle_homepage");
		$default_app3->setType("admin");
		$default_app3->setDescription("Manage setting values.");
		$default_app3->setActivated(true);
		$default_app3->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app3);
		$em->flush();
		
		$default_app4 = new Application();
		$default_app4->setName("Reports");
		$default_app4->setBundleName("AdminLogsBundle");
		$default_app4->setLink("AdminLogsBundle_homepage");
		$default_app4->setType("admin");
		$default_app4->setDescription("View Scube's Logs and find errors");
		$default_app4->setActivated(true);
		$default_app4->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app4);
		$em->flush();
		
		$default_app5 = new Application();
		$default_app5->setName("Connections");
		$default_app5->setBundleName("ConnectionsBundle");
		$default_app5->setLink("ConnectionsBundle_homepage");
		$default_app5->setType("normal");
		$default_app5->setDescription("Organize your contacts");
		$default_app5->setActivated(true);
		$default_app5->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app5);
		$em->flush();
			/* Widgets de Connections */
			$default_wid = new Widget();
			$default_wid->setName("Connections");
			$default_wid->setApplication($default_app5);
			$default_wid->setLink("WidgetsConnectionsWidgetBundle_homepage");
			$default_wid->setBundleName("ConnectionsWidgetBundle");
			$default_wid->setMinWidth(1);
			$default_wid->setMaxWidth(1);
			$default_wid->setMinHeight(1);
			$default_wid->setMaxHeight(1);
			$default_wid->setFullscreen(false);
			$default_wid->setType("button");
			$default_wid->setButtonLink("ConnectionsBundle_homepage");
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($default_wid);
			$em->flush();
		
		$default_app6 = new Application();
		$default_app6->setName("Account");
		$default_app6->setBundleName("AccountBundle");
		$default_app6->setLink("ScubeAccountBundle_homepage");
		$default_app6->setType("normal");
		$default_app6->setDescription("Manage your account, profile and pictures");
		$default_app6->setActivated(true);
		$default_app6->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app6);
		$em->flush();
		
		$default_app7 = new Application();
		$default_app7->setName("Calendar");
		$default_app7->setBundleName("CalendarBundle");
		$default_app7->setLink("CalendarBundle_homepage");
		$default_app7->setType("normal");
		$default_app7->setDescription("Manage events in your personal calendar");
		$default_app7->setActivated(true);
		$default_app7->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app7);
		$em->flush();
			/* Widgets de Calendar */
			$default_wid2 = new Widget();
			$default_wid2->setName("Calendar");
			$default_wid2->setApplication($default_app7);
			$default_wid2->setLink("WidgetsCalendarWidgetBundle_homepage");
			$default_wid2->setBundleName("CalendarWidgetBundle");
			$default_wid2->setMinWidth(1);
			$default_wid2->setMaxWidth(1);
			$default_wid2->setMinHeight(1);
			$default_wid2->setMaxHeight(1);
			$default_wid2->setFullscreen(false);
			$default_wid2->setType("button");
			$default_wid2->setButtonLink("CalendarBundle_homepage");
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($default_wid2);
			$em->flush();
		
		$default_app8 = new Application();
		$default_app8->setName("Mailbox");
		$default_app8->setBundleName("MailboxBundle");
		$default_app8->setLink("ScubeMailboxBundle_homepage");
		$default_app8->setType("normal");
		$default_app8->setDescription("Send messages to your contacts");
		$default_app8->setActivated(true);
		$default_app8->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app8);
		$em->flush();
			/* Widgets de Mailbox */
			$default_wid3 = new Widget();
			$default_wid3->setName("Mailbox");
			$default_wid3->setApplication($default_app8);
			$default_wid3->setLink("WidgetsMailboxWidgetBundle_homepage");
			$default_wid3->setBundleName("MailboxWidgetBundle");
			$default_wid3->setMinWidth(1);
			$default_wid3->setMaxWidth(1);
			$default_wid3->setMinHeight(1);
			$default_wid3->setMaxHeight(1);
			$default_wid3->setFullscreen(false);
			$default_wid3->setType("button");
			$default_wid3->setButtonLink("ScubeMailboxBundle_homepage");
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($default_wid3);
			$em->flush();
		
		$default_app9 = new Application();
		$default_app9->setName("Appearance");
		$default_app9->setBundleName("AdminAppearanceBundle");
		$default_app9->setLink("ScubeAdminAppearanceBundle_homepage");
		$default_app9->setType("admin");
		$default_app9->setDescription("Manage appearance as your own");
		$default_app9->setActivated(true);
		$default_app9->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app9);
		$em->flush();
		
		$default_app10 = new Application();
		$default_app10->setName("Medias");
		$default_app10->setBundleName("MediasBundle");
		$default_app10->setLink("ScubeMediasBundle_homepage");
		$default_app10->setType("normal");
		$default_app10->setDescription("Upload and watch your media files");
		$default_app10->setActivated(true);
		$default_app10->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app10);
		$em->flush();
			/* Widgets de Mailbox */
			$default_wid4 = new Widget();
			$default_wid4->setName("Medias");
			$default_wid4->setApplication($default_app10);
			$default_wid4->setLink("WidgetsMediasWidgetBundle_homepage");
			$default_wid4->setBundleName("MediasWidgetBundle");
			$default_wid4->setMinWidth(1);
			$default_wid4->setMaxWidth(1);
			$default_wid4->setMinHeight(1);
			$default_wid4->setMaxHeight(1);
			$default_wid4->setFullscreen(false);
			$default_wid4->setType("button");
			$default_wid4->setButtonLink("ScubeMediasBundle_homepage");
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($default_wid4);
			$em->flush();
			
		$default_app11 = new Application();
		$default_app11->setName("Help");
		$default_app11->setBundleName("AdminHelpBundle");
		$default_app11->setLink("ScubeAdminHelpBundle_homepage");
		$default_app11->setType("admin");
		$default_app11->setDescription("View Scube's admin Help");
		$default_app11->setActivated(true);
		$default_app11->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app11);
		$em->flush();
		
		/* Default Settings */
		$default_setting = new ScubeSetting();
		$default_setting->setKey("allow_registration");
		$default_setting->setValue("1");
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_setting);
		$em->flush();
		
		$default_setting2 = new ScubeSetting();
		$default_setting2->setKey("dashboard_cell_width");
		$default_setting2->setValue("7");
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_setting2);
		$em->flush();
		
		$default_setting3 = new ScubeSetting();
		$default_setting3->setKey("dashboard_cell_height");
		$default_setting3->setValue("5");
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_setting3);
		$em->flush();
		
		$default_setting4 = new ScubeSetting();
		$default_setting4->setKey("profile_picture_width");
		$default_setting4->setValue("200");
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_setting4);
		$em->flush();
		
		$default_setting5 = new ScubeSetting();
		$default_setting5->setKey("profile_picture_height");
		$default_setting5->setValue("200");
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_setting5);
		$em->flush();
		
		$default_setting6 = new ScubeSetting();
		$default_setting6->setKey("default_locale");
		$default_setting6->setValue("uk");
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_setting6);
		$em->flush();
		
		/* Default Permissions Groups */
		
		// admin
		$default_grp = new PermissionsGroup();
		$default_grp->setName("administrator");
		$default_grp->setLocked(true);
		
		$default_grp->addApplication($default_app5);
		$default_grp->addApplication($default_app6);
		$default_grp->addApplication($default_app7);
		$default_grp->addApplication($default_app8);
		$default_grp->addApplication($default_app10);
		
		$default_grp->addAdminApplication($default_app);
		$default_grp->addAdminApplication($default_app2);
		$default_grp->addAdminApplication($default_app3);
		$default_grp->addAdminApplication($default_app4);
		$default_grp->addAdminApplication($default_app9);
		$default_grp->addAdminApplication($default_app11);
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_grp);
		$em->flush();
		
		// default
		$default_grp2 = new PermissionsGroup();
		$default_grp2->setName("default");
		$default_grp2->setLocked(true);
		
		$default_grp2->addApplication($default_app5);
		$default_grp2->addApplication($default_app6);
		$default_grp2->addApplication($default_app7);
		$default_grp2->addApplication($default_app8);
		$default_grp2->addApplication($default_app10);
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_grp2);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ScubeInstallBundle_homepage'));
	}
    
    public function indexAction(Request $request)
    {
		$user = new User();
		$form = $this->createFormBuilder($user)
            ->add('Firstname', 'text')
            ->add('Surname', 'text')
			->add('Email', 'email')
			->add('Password', 'password')
			->add('Birthday', 'birthday')
			->add('Gender', 'choice', array('choices' => array('male' => 'Male', 'female' => 'Female')))
            ->getForm();
            
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
				/* Set profile object */
				$profile = new UserProfile();
				/* Set interface object */
				$interface = new BaseInterface();
				/* Set calendar object */
				$calendar = new Calendar();
				/* Set mailbox object */
				$mailbox = new Mailbox();
				
				$user->setPermissionsGroup($this->getDoctrine()->getRepository('ScubeBaseBundle:PermissionsGroup')->findOneBy(array('name' => "administrator")));
				$user->setOnline(false);
				$user->setBlocked(false);
				$user->setDateRegister(new \DateTime());
				$user->setDateLastAccess(new \DateTime());
				$user->setLocale($this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "default_locale"))->getValue());
				
				$user->setProfile($profile);
				$user->setBaseInterface($interface);
				$user->setCalendar($calendar);
				$user->setMailbox($mailbox);
				
				
				
				$default_usr_interface_widget = new InterfaceWidget();
				$default_usr_interface_widget->setWidth(1);
				$default_usr_interface_widget->setHeight(1);
				$default_usr_interface_widget->setPosX(1);
				$default_usr_interface_widget->setPosY(1);
				$default_usr_interface_widget->setWidget($this->getDoctrine()->getRepository('ScubeBaseBundle:Widget')->findOneBy(array('name' => "Connections")));
				$interface->addInterfaceWidget($default_usr_interface_widget);
			
				$default_usr_interface_widget2 = new InterfaceWidget();
				$default_usr_interface_widget2->setWidth(1);
				$default_usr_interface_widget2->setHeight(1);
				$default_usr_interface_widget2->setPosX(5);
				$default_usr_interface_widget2->setPosY(1);
				$default_usr_interface_widget2->setWidget($this->getDoctrine()->getRepository('ScubeBaseBundle:Widget')->findOneBy(array('name' => "Calendar")));
				$interface->addInterfaceWidget($default_usr_interface_widget2);
			
				$default_usr_interface_widget3 = new InterfaceWidget();
				$default_usr_interface_widget3->setWidth(1);
				$default_usr_interface_widget3->setHeight(1);
				$default_usr_interface_widget3->setPosX(2);
				$default_usr_interface_widget3->setPosY(1);
				$default_usr_interface_widget3->setWidget($this->getDoctrine()->getRepository('ScubeBaseBundle:Widget')->findOneBy(array('name' => "Mailbox")));
				$interface->addInterfaceWidget($default_usr_interface_widget3);
			
				$default_usr_interface_widget4 = new InterfaceWidget();
				$default_usr_interface_widget4->setWidth(1);
				$default_usr_interface_widget4->setHeight(1);
				$default_usr_interface_widget4->setPosX(4);
				$default_usr_interface_widget4->setPosY(1);
				$default_usr_interface_widget4->setWidget($this->getDoctrine()->getRepository('ScubeBaseBundle:Widget')->findOneBy(array('name' => "Medias")));
				$interface->addInterfaceWidget($default_usr_interface_widget4);
		
		
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($profile);
				$em->persist($default_usr_interface_widget);
				$em->persist($default_usr_interface_widget2);
				$em->persist($default_usr_interface_widget3);
				$em->persist($default_usr_interface_widget4);
				$em->persist($interface);
				$em->persist($calendar);
				$em->persist($mailbox);
				$em->persist($user);
				$em->flush();
				
				\Scube\BaseBundle\Controller\BaseController::createUserDirectory($this->get('kernel'), $user);
				
				return $this->render('ScubeInstallBundle:Default:index.html.twig', array('form' => $form->createView(), 'success' => true));
			}
		}

		return $this->render('ScubeInstallBundle:Default:index.html.twig', array('form' => $form->createView()));
    }
}

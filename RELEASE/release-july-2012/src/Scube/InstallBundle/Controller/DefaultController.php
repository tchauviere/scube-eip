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


class DefaultController extends Controller
{
    
    public function indexAction()
    {
		/* /!!!\ Doit appell� par le script d'installation !!! -> Reset des champs */
		
		/* Default Application */
		$default_app = new Application();
		$default_app->setName("Application manager for administrator");
		$default_app->setBundleName("");
		$default_app->setAdminBundleName("AdminAppsBundle");
		$default_app->setLink("");
		$default_app->setAdminLink("AdminAppsBundle_homepage");
		$default_app->setDescription("Manage installed applications on your server and install new applications now !");
		$default_app->setActivated(true);
		$default_app->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app);
		$em->flush();
		
		$default_app2 = new Application();
		$default_app2->setName("User manager for administrator");
		$default_app2->setBundleName("");
		$default_app2->setAdminBundleName("AdminUserBundle");
		$default_app2->setLink("");
		$default_app2->setAdminLink("AdminUserBundle_homepage");
		$default_app2->setDescription("Manage users, groups and permissions.");
		$default_app2->setActivated(true);
		$default_app2->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app2);
		$em->flush();
		
		$default_app3 = new Application();
		$default_app3->setName("Settings manager for administrator");
		$default_app3->setBundleName("");
		$default_app3->setAdminBundleName("AdminSettingsBundle");
		$default_app3->setLink("");
		$default_app3->setAdminLink("AdminSettingsBundle_homepage");
		$default_app3->setDescription("Manage setting values.");
		$default_app3->setActivated(true);
		$default_app3->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app3);
		$em->flush();
		
		$default_app4 = new Application();
		$default_app4->setName("Server Logs Manager for administrator");
		$default_app4->setBundleName("");
		$default_app4->setAdminBundleName("AdminLogsBundle");
		$default_app4->setLink("");
		$default_app4->setAdminLink("AdminLogsBundle_homepage");
		$default_app4->setDescription("View Scube's Logs and find errors");
		$default_app4->setActivated(true);
		$default_app4->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app4);
		$em->flush();
		
		$default_app5 = new Application();
		$default_app5->setName("Connections");
		$default_app5->setBundleName("ConnectionsBundle");
		$default_app5->setAdminBundleName("");
		$default_app5->setLink("ConnectionsBundle_homepage");
		$default_app5->setAdminLink("");
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
			$default_wid->setFullscreen(true);
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($default_wid);
			$em->flush();
		
		$default_app6 = new Application();
		$default_app6->setName("Account");
		$default_app6->setBundleName("AccountBundle");
		$default_app6->setAdminBundleName("");
		$default_app6->setLink("ScubeAccountBundle_homepage");
		$default_app6->setAdminLink("");
		$default_app6->setDescription("Manage your account, profile and pictures");
		$default_app6->setActivated(true);
		$default_app6->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app6);
		$em->flush();
		
		$default_app7 = new Application();
		$default_app7->setName("Calendar");
		$default_app7->setBundleName("CalendarBundle");
		$default_app7->setAdminBundleName("");
		$default_app7->setLink("CalendarBundle_homepage");
		$default_app7->setAdminLink("");
		$default_app7->setDescription("Manage events in your personal calendar");
		$default_app7->setActivated(true);
		$default_app7->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app7);
		$em->flush();
		
		$default_app8 = new Application();
		$default_app8->setName("Mailbox");
		$default_app8->setBundleName("MailboxBundle");
		$default_app8->setAdminBundleName("");
		$default_app8->setLink("ScubeMailboxBundle_homepage");
		$default_app8->setAdminLink("");
		$default_app8->setDescription("Send messages to your contacts");
		$default_app8->setActivated(true);
		$default_app8->setNecessary(true);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_app8);
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
		
		/* Default Permissions Groups */
		
		// admin
		$default_grp = new PermissionsGroup();
		$default_grp->setName("administrator");
		
		$default_grp->addApplication($default_app5);
		$default_grp->addApplication($default_app6);
		$default_grp->addApplication($default_app7);
		$default_grp->addApplication($default_app8);
		
		$default_grp->addAdminApplication($default_app);
		$default_grp->addAdminApplication($default_app2);
		$default_grp->addAdminApplication($default_app3);
		$default_grp->addAdminApplication($default_app4);
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_grp);
		$em->flush();
		
		// default
		$default_grp2 = new PermissionsGroup();
		$default_grp2->setName("default");
		
		$default_grp2->addApplication($default_app5);
		$default_grp2->addApplication($default_app6);
		$default_grp2->addApplication($default_app7);
		$default_grp2->addApplication($default_app8);
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_grp2);
		$em->flush();
		
		/* Default Administrator */
		
		$default_usr = new User();
		$default_usr->setFirstname("Epitech");
		$default_usr->setSurname("Paris");
		$default_usr->setEmail("scube@gmail.com");
		$default_usr->setPassword("scubeeip");
		$default_usr->setBirthday(new \DateTime());
		$default_usr->setGender("male");
		
		$default_usr_profile = new UserProfile();
		$default_usr_calendar = new Calendar();
		$default_usr_mailbox = new Mailbox();
		$default_usr_interface = new BaseInterface();
			$default_usr_interface_widget = new InterfaceWidget();
			$default_usr_interface_widget->setWidth(1);
			$default_usr_interface_widget->setHeight(1);
			$default_usr_interface_widget->setPosX(1);
			$default_usr_interface_widget->setPosY(1);
			$default_usr_interface_widget->setWidget($default_wid);
			$default_usr_interface->addInterfaceWidget($default_usr_interface_widget);
		
		$default_usr->setProfile($default_usr_profile);
		$default_usr->setBaseInterface($default_usr_interface);
		$default_usr->setCalendar($default_usr_calendar);
		$default_usr->setMailbox($default_usr_mailbox);
		$default_usr->setPermissionsGroup($default_grp);
		
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_usr_profile);
		$em->persist($default_usr_interface_widget);
		$em->persist($default_usr_interface);
		$em->persist($default_usr_calendar);
		$em->persist($default_usr_mailbox);
		$em->persist($default_usr);
		$em->flush();
		
		\Scube\BaseBundle\Controller\BaseController::createUserDirectory($this->get('kernel'), $default_usr);
		
		
        return $this->render('ScubeInstallBundle:Default:index.html.twig', array('name' => false));
    }
}

<?php

namespace Scube\InstallBundle\Controller;

use Scube\BaseBundle\Entity\Application;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;
use Scube\BaseBundle\Entity\BaseInterface;
use Scube\BaseBundle\Entity\PermissionsGroup;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
		/* /!!!\ Doit appellé par le script d'installation !!! -> Reset des champs */
		
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
		
		/* Default Permissions Groups */
		
		// admin
		$default_grp = new PermissionsGroup();
		$default_grp->setName("administrator");
		
		$default_grp->addAdminApplication($default_app);
		$default_grp->addAdminApplication($default_app2);
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_grp);
		$em->flush();
		
		// default
		$default_grp2 = new PermissionsGroup();
		$default_grp2->setName("default");
		
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
		$default_usr_interface = new BaseInterface();
		$default_usr->setProfile($default_usr_profile);
		$default_usr->setBaseInterface($default_usr_interface);
		$default_usr->setPermissionsGroup($default_grp);
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($default_usr_profile);
		$em->persist($default_usr_interface);
		$em->persist($default_usr);
		$em->flush();
		
		
        return $this->render('ScubeInstallBundle:Default:index.html.twig', array('name' => false));
    }
}

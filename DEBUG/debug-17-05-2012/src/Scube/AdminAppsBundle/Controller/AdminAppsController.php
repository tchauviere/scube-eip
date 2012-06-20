<?php

namespace Scube\AdminAppsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\Application;
use Scube\BaseBundle\Entity\Widget;

class AdminAppsController extends Controller
{
    
    public function indexAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Application a ORDER BY a.name ASC");
		$app_list = $query->getResult();
		return $this->render('ScubeAdminAppsBundle:AdminApps:index.html.twig', array('app_list'=>$app_list));
    }
	
	public function activateAction(Request $request, $id, $val)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$application = $em->getRepository('ScubeBaseBundle:Application')->find($id);
	
		if (!$application) {
			throw $this->createNotFoundException('No application found for id '.$id);
		}
		
		if ($application->getNecessary() == false)
		{
			$application->setActivated($val);
		}
		$em->flush();
		return $this->redirect($this->generateUrl('AdminAppsBundle_homepage'));
    }
	
	public function removeAction(Request $request, $id)
    {
		$url_redirect = $this->generateUrl('AdminAppsBundle_homepage'); // doit etre mis ici et surtout pas apres la modification du routing (sinon gros gros bug)
		
		$em = $this->getDoctrine()->getEntityManager();
		$application = $em->getRepository('ScubeBaseBundle:Application')->find($id);
	
		if (!$application) {
			throw $this->createNotFoundException('No application found for id '.$id);
		}
		
		if ($application->getNecessary() == false)
		{
			/* DELETE THE BUNDLE IN THE SRC FOLDER */
			 if ($application->getBundleName())
			 {
				$this->rrmdir($this->get('kernel')->getRootDir()."/../src/Apps/".$application->getBundleName());
				$bundle_path = "new Apps\\".$application->getBundleName()."\\Apps".$application->getBundleName()."(),\n";
				$bundle_routing = "Apps".$application->getBundleName().":\n    resource: \"@Apps".$application->getBundleName()."/Resources/config/routing.yml\"\n    prefix:   /\n";
			 }
			 if ($application->getAdminBundleName())
			 {
				$this->rrmdir($this->get('kernel')->getRootDir()."/../src/Apps/".$application->getAdminBundleName());
				$admin_bundle_path = "new Apps\\".$application->getAdminBundleName()."\\Apps".$application->getAdminBundleName()."(),\n";
				$admin_bundle_routing = "Apps".$application->getAdminBundleName().":\n    resource: \"@Apps".$application->getAdminBundleName()."/Resources/config/routing.yml\"\n    prefix:   /\n";
			 }
			 
			 /* DELETE BUNDLE FROM KERNEL */
			 if (is_writable($this->get('kernel')->getRootDir()."/AppKernel.php"))
			 {
				 $kernel_content = file_get_contents($this->get('kernel')->getRootDir()."/AppKernel.php");
				 if ($application->getBundleName())
					$kernel_content = str_replace($bundle_path, "", $kernel_content);
				if ($application->getAdminBundleName())
					$kernel_content = str_replace($admin_bundle_path, "", $kernel_content);
					
				file_put_contents($this->get('kernel')->getRootDir()."/AppKernel.php", $kernel_content);
			 }
			 else
			 {
				 throw $this->createNotFoundException('Unable to access to the file ./app/AppKernel.php Please check permissions.');
			 }
			 
			 /* DELETE BUNDLE FROM ROUTING */
			 if (is_writable($this->get('kernel')->getRootDir()."/config/routing.yml"))
			 {
				 $routing_content = file_get_contents($this->get('kernel')->getRootDir()."/config/routing.yml");
				 if ($application->getBundleName())
					$routing_content = str_replace($bundle_routing, "", $routing_content);
				if ($application->getAdminBundleName())
					$routing_content = str_replace($admin_bundle_routing, "", $routing_content);
					
				file_put_contents($this->get('kernel')->getRootDir()."/config/routing.yml", $routing_content);
			 }
			 else
			 {
				 throw $this->createNotFoundException('Unable to access to the file ./app/config/routing.yml Please check permissions.');
			 }
			
			/* DELETE BUNDLE FROM DATABASES */
			$em->remove($application);
		}
		$em->flush();
		return $this->redirect($url_redirect);
    }
	
	public function installAction(Request $request)
    {
		$url_redirect = $this->generateUrl('AdminAppsBundle_homepage'); // doit etre mis ici et surtout pas apres la modification du routing (sinon gros gros bug)
		
		$defaultData = array();
		$form = $this->createFormBuilder($defaultData)
			->add('zipped_application', 'file')
			->getForm();
	
			if ($request->getMethod() == 'POST') {
				$form->bindRequest($request);
	
				$data = $form->getData();
				$filename = "install_app_archive.zip";
				
				/* COPY OF THE INPUT FILE IN THE TEMPORARY FOLDER */
				$form['zipped_application']->getData()->move($this->get('kernel')->getRootDir()."/../TEMPORARY/", $filename);
				
				/* Unzip application and put in the TEMPORARY FOLDER */
				$zip = new \ZipArchive;
				 $res = $zip->open($this->get('kernel')->getRootDir()."/../TEMPORARY/".$filename);
				 if ($res === TRUE) {
					 $zip->extractTo($this->get('kernel')->getRootDir()."/../TEMPORARY/install_app_archive");
					 $zip->close();
					 
					 /* READ THE MANIFEST TO FIND APPLICATION INFORMATIONS */
					 $manifest_content = file_get_contents($this->get('kernel')->getRootDir()."/../TEMPORARY/install_app_archive/manifest.txt");
					 $manifest_line = explode("\n", $manifest_content);
					 $manifest = array();
					 foreach ($manifest_line as $line)
					 {
						 if (!trim($line))
						 	continue;
						 list($key, $value) = explode(":", $line);
						 if ($key)
						 	$manifest[$key] = trim($value);
					 }
					 
					 /* MOVE BUNDLES INTO THE SRC FOLDER */
					 if ($manifest['bundle_name'])
					 {
						 $res = rename($this->get('kernel')->getRootDir()."/../TEMPORARY/install_app_archive/".$manifest['bundle_name'], $this->get('kernel')->getRootDir()."/../src/Apps/".$manifest['bundle_name']);
						 if ($res == false)
						 	throw $this->createNotFoundException('Unable to access to the folder ./src/Apps/ Please check permissions.');
						$bundle_path = "new Apps\\".$manifest['bundle_name']."\\Apps".$manifest['bundle_name']."(),\n";
						$bundle_routing = "Apps".$manifest['bundle_name'].":\n    resource: \"@Apps".$manifest['bundle_name']."/Resources/config/routing.yml\"\n    prefix:   /\n";
					 }
					 if ($manifest['admin_bundle_name'])
					 {
						 $res = rename($this->get('kernel')->getRootDir()."/../TEMPORARY/install_app_archive/".$manifest['admin_bundle_name'], $this->get('kernel')->getRootDir()."/../src/Apps/".$manifest['admin_bundle_name']);
						 if ($res == false)
						 	throw $this->createNotFoundException('Unable to access to the folder ./src/Apps/ Please check permissions.');
						$admin_bundle_name = "new Apps\\".$manifest['admin_bundle_name']."\\Apps".$manifest['admin_bundle_name']."(),\n";
						$admin_bundle_routing = "Apps".$manifest['admin_bundle_name'].":\n    resource: \"@Apps".$manifest['admin_bundle_name']."/Resources/config/routing.yml\"\n    prefix:   /\n";
					 }
					 
					 /* KERNEL MODIFICATION */
					 if (is_writable($this->get('kernel')->getRootDir()."/AppKernel.php"))
					 {
						 $kernel_content = file_get_contents($this->get('kernel')->getRootDir()."/AppKernel.php");
						 if ($manifest['bundle_name'])
						 	$kernel_content = str_replace("/*APP_DELIMITER*/", "/*APP_DELIMITER*/\n".$bundle_path, $kernel_content);
						if ($manifest['admin_bundle_name'])
						 	$kernel_content = str_replace("/*APP_DELIMITER*/", "/*APP_DELIMITER*/\n".$admin_bundle_name, $kernel_content);
							
						file_put_contents($this->get('kernel')->getRootDir()."/AppKernel.php", $kernel_content);
					 }
					 else
					 {
						 throw $this->createNotFoundException('Unable to access to the file ./app/AppKernel.php Please check permissions.');
					 }
					 
					 /* GENERAL ROUTING CONFIGURATION */
					 if (is_writable($this->get('kernel')->getRootDir()."/config/routing.yml"))
					 {
						 $routing_content = file_get_contents($this->get('kernel')->getRootDir()."/config/routing.yml");
						 if ($manifest['bundle_name'])
						 	$routing_content = str_replace("#APP_DELIMITER", "#APP_DELIMITER\n".$bundle_routing, $routing_content);
						if ($manifest['admin_bundle_name'])
						 	$routing_content = str_replace("#APP_DELIMITER", "#APP_DELIMITER\n".$admin_bundle_routing, $routing_content);
							
						file_put_contents($this->get('kernel')->getRootDir()."/config/routing.yml", $routing_content);
					 }
					 else
					 {
						 throw $this->createNotFoundException('Unable to access to the file ./app/config/routing.yml Please check permissions.');
					 }
					 
					 /* REMOVE TEMPORARY FILES */
					 if (file_exists($this->get('kernel')->getRootDir()."/../TEMPORARY/install_app_archive/"))
					 	$this->rrmdir($this->get('kernel')->getRootDir()."/../TEMPORARY/install_app_archive/");
						
					 if (file_exists($this->get('kernel')->getRootDir()."/../TEMPORARY/".$filename))
					 	unlink($this->get('kernel')->getRootDir()."/../TEMPORARY/".$filename);
						
					 /* CREATE THE APPLICATION OBJECT TO SAVE IT */
					 $application = new Application();
					 $application->setName($manifest['name']);
					 $application->setBundleName($manifest['bundle_name']);
					 $application->setAdminBundleName($manifest['admin_bundle_name']);
					 $application->setLink($manifest['link']);
					 $application->setAdminLink($manifest['admin_link']);
					 $application->setDescription($manifest['description']);
					 $application->setActivated($manifest['activated']);
					 $application->setNecessary($manifest['necessary']);
					 
					 $em = $this->getDoctrine()->getEntityManager();
					 $em->persist($application);
					 $em->flush();
					 
					 return $this->redirect($url_redirect);
					 
				 } else {
					throw $this->createNotFoundException('Unable to access to the folder ./TEMPORARY Please check permissions.');
				 }
			}
		return $this->render('ScubeAdminAppsBundle:AdminApps:install.html.twig', array('form' => $form->createView(), 'success'=>false));
    }
	
	public function indexWidgetAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Widget a ORDER BY a.name ASC");
		$widget_list = $query->getResult();
		return $this->render('ScubeAdminAppsBundle:AdminApps:index_widget.html.twig', array('widget_list'=>$widget_list));
    }
	
	public function installWidgetAction(Request $request)
    {
		$url_redirect = $this->generateUrl('AdminAppsBundle_widget_homepage'); // doit etre mis ici et surtout pas apres la modification du routing (sinon gros gros bug)
		
		$defaultData = array();
		$form = $this->createFormBuilder($defaultData)
			->add('zipped_application', 'file')
			->getForm();
	
			if ($request->getMethod() == 'POST') {
				$form->bindRequest($request);
	
				$data = $form->getData();
				$filename = "install_widget_archive.zip";
				
				/* COPY OF THE INPUT FILE IN THE TEMPORARY FOLDER */
				$form['zipped_application']->getData()->move($this->get('kernel')->getRootDir()."/../TEMPORARY/", $filename);
				
				/* Unzip application and put in the TEMPORARY FOLDER */
				$zip = new \ZipArchive;
				 $res = $zip->open($this->get('kernel')->getRootDir()."/../TEMPORARY/".$filename);
				 if ($res === TRUE) {
					 $zip->extractTo($this->get('kernel')->getRootDir()."/../TEMPORARY/install_widget_archive");
					 $zip->close();
					 
					 /* READ THE MANIFEST TO FIND APPLICATION INFORMATIONS */
					 $manifest_content = file_get_contents($this->get('kernel')->getRootDir()."/../TEMPORARY/install_widget_archive/manifest.txt");
					 $manifest_line = explode("\n", $manifest_content);
					 $manifest = array();
					 foreach ($manifest_line as $line)
					 {
						 if (!trim($line))
						 	continue;
						 list($key, $value) = explode(":", $line);
						 if ($key)
						 	$manifest[$key] = trim($value);
					 }
					 
					 /* MOVE BUNDLES INTO THE SRC FOLDER */
					 if ($manifest['bundle_name'])
					 {
						 $res = rename($this->get('kernel')->getRootDir()."/../TEMPORARY/install_widget_archive/".$manifest['bundle_name'], $this->get('kernel')->getRootDir()."/../src/Widgets/".$manifest['bundle_name']);
						 if ($res == false)
						 	throw $this->createNotFoundException('Unable to access to the folder ./src/Widgets/ Please check permissions.');
						$bundle_path = "new Widgets\\".$manifest['bundle_name']."\\Widgets".$manifest['bundle_name']."(),\n";
						$bundle_routing = "Widgets".$manifest['bundle_name'].":\n    resource: \"@Widgets".$manifest['bundle_name']."/Resources/config/routing.yml\"\n    prefix:   /\n";
					 }
					 
					 /* KERNEL MODIFICATION */
					 if (is_writable($this->get('kernel')->getRootDir()."/AppKernel.php"))
					 {
						 $kernel_content = file_get_contents($this->get('kernel')->getRootDir()."/AppKernel.php");
						 if ($manifest['bundle_name'])
						 	$kernel_content = str_replace("/*APP_DELIMITER*/", "/*APP_DELIMITER*/\n".$bundle_path, $kernel_content);
							
						file_put_contents($this->get('kernel')->getRootDir()."/AppKernel.php", $kernel_content);
					 }
					 else
					 {
						 throw $this->createNotFoundException('Unable to access to the file ./app/AppKernel.php Please check permissions.');
					 }
					 
					 /* GENERAL ROUTING CONFIGURATION */
					 if (is_writable($this->get('kernel')->getRootDir()."/config/routing.yml"))
					 {
						 $routing_content = file_get_contents($this->get('kernel')->getRootDir()."/config/routing.yml");
						 if ($manifest['bundle_name'])
						 	$routing_content = str_replace("#APP_DELIMITER", "#APP_DELIMITER\n".$bundle_routing, $routing_content);
							
						file_put_contents($this->get('kernel')->getRootDir()."/config/routing.yml", $routing_content);
					 }
					 else
					 {
						 throw $this->createNotFoundException('Unable to access to the file ./app/config/routing.yml Please check permissions.');
					 }
					 
					 /* REMOVE TEMPORARY FILES */
					 if (file_exists($this->get('kernel')->getRootDir()."/../TEMPORARY/install_widget_archive/"))
					 	$this->rrmdir($this->get('kernel')->getRootDir()."/../TEMPORARY/install_widget_archive/");
						
					 if (file_exists($this->get('kernel')->getRootDir()."/../TEMPORARY/".$filename))
					 	unlink($this->get('kernel')->getRootDir()."/../TEMPORARY/".$filename);
						
					 /* CREATE THE APPLICATION OBJECT TO SAVE IT */
					 if (isset($manifest['application_bundle_name']))
					 {
					 	$em = $this->getDoctrine()->getEntityManager();
					 	$application = $em->getRepository('ScubeBaseBundle:Application')->findOneBy(array('bundle_name' => $manifest['application_bundle_name']));
					 }
					 
					 $widget = new Widget();
					 if (isset($application))
					 	$widget->setApplication($application);
					 $widget->setLink($manifest['link']);
					 $widget->setName($manifest['name']);
					 $widget->setBundleName($manifest['bundle_name']);
					 $widget->setMinWidth(intval($manifest['min_width']));
					 $widget->setMaxWidth(intval($manifest['max_width']));
					 $widget->setMinHeight(intval($manifest['min_height']));
					 $widget->setMaxHeight(intval($manifest['max_height']));
					 $widget->setFullscreen(intval($manifest['fullscreen']));
					 
					 $em = $this->getDoctrine()->getEntityManager();
					 $em->persist($widget);
					 $em->flush();
					 
					 return $this->redirect($url_redirect);
					 
				 } else {
					throw $this->createNotFoundException('Unable to access to the folder ./TEMPORARY Please check permissions.');
				 }
			}
		return $this->render('ScubeAdminAppsBundle:AdminApps:widget_install.html.twig', array('form' => $form->createView(), 'success'=>false));
    }
	public function removeWidgetAction(Request $request, $id)
    {
		$url_redirect = $this->generateUrl('AdminAppsBundle_widget_homepage'); // doit etre mis ici et surtout pas apres la modification du routing (sinon gros gros bug)
		
		$em = $this->getDoctrine()->getEntityManager();
		$widget = $em->getRepository('ScubeBaseBundle:Widget')->find($id);
	
		if (!$widget) {
			throw $this->createNotFoundException('No widget found for id '.$id);
		}
		
		
		/* DELETE THE BUNDLE IN THE SRC FOLDER */
		 if ($widget->getBundleName())
		 {
			$this->rrmdir($this->get('kernel')->getRootDir()."/../src/Widgets/".$widget->getBundleName());
			$bundle_path = "new Widgets\\".$widget->getBundleName()."\\Widgets".$widget->getBundleName()."(),\n";
			$bundle_routing = "Widgets".$widget->getBundleName().":\n    resource: \"@Widgets".$widget->getBundleName()."/Resources/config/routing.yml\"\n    prefix:   /\n";
		 }
		 
		 /* DELETE BUNDLE FROM KERNEL */
		 if (is_writable($this->get('kernel')->getRootDir()."/AppKernel.php"))
		 {
			 $kernel_content = file_get_contents($this->get('kernel')->getRootDir()."/AppKernel.php");
			 if ($widget->getBundleName())
				$kernel_content = str_replace($bundle_path, "", $kernel_content);
				
			file_put_contents($this->get('kernel')->getRootDir()."/AppKernel.php", $kernel_content);
		 }
		 else
		 {
			 throw $this->createNotFoundException('Unable to access to the file ./app/AppKernel.php Please check permissions.');
		 }
		 
		 /* DELETE BUNDLE FROM ROUTING */
		 if (is_writable($this->get('kernel')->getRootDir()."/config/routing.yml"))
		 {
			 $routing_content = file_get_contents($this->get('kernel')->getRootDir()."/config/routing.yml");
			 if ($widget->getBundleName())
				$routing_content = str_replace($bundle_routing, "", $routing_content);
				
			file_put_contents($this->get('kernel')->getRootDir()."/config/routing.yml", $routing_content);
		 }
		 else
		 {
			 throw $this->createNotFoundException('Unable to access to the file ./app/config/routing.yml Please check permissions.');
		 }
		
		/* DELETE BUNDLE FROM DATABASES */
		$em->remove($widget);
		
		$em->flush();
		return $this->redirect($url_redirect);
    }
	
    function rrmdir($dir) 
	{
		foreach(array_merge(glob($dir . '/*'), glob($dir . '/.svn')) as $file) {
			if(is_dir($file))
				$this->rrmdir($file);
			else
				unlink($file);
		}
		rmdir($dir);
	}
}

<?php

namespace Scube\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;

class CoreController extends Controller
{
	protected $user;
	
    public function __construct() 
	{
		$this->user = NULL;
		$this->application = NULL;
	}
	
	protected function preprocessApplication()
	{
		$this->checkUser();
		$this->checkLocale();
		$this->checkMaintenance();
		$this->checkBlackList();
		$this->checkAccess();
	}
	
	/*
	 * /!\ Must be called after checkUser() 
	 * Check the user Locale for translations and localization
	 */
	public function	checkLocale()
	{
		$this->get('session')->setLocale($this->user->getLocale());
	}
	
	/*
	 * /!\ Must be called after checkUser() 
	 * Check if maintenance mode is currently activated
	 * If true, check if current user is in the authorized user list
	 */
	public function	checkMaintenance()
	{
		
	}
	
	/*
	 * /!\ Must be called after checkUser() 
	 * Check if current user is currently blocked or in the black list
	 */
	public function	checkBlackList()
	{
		if ($this->user->getBlocked()) {
			throw new \Exception('You have been blocked by the administrator.');
		}
	}
	
	/*
	 * /!\ Must be called after checkUser() 
	 * Check the user has permissions on the application
	 */
	public function	checkAccess()
	{
		$current_route = $this->container->get('request')->get('_route');
		
		list($route_for_query) = explode("_", $current_route);
		
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT a FROM ScubeBaseBundle:Application a WHERE a.link LIKE :link")
					->setParameter('link', $route_for_query.'%');
					
		$current_app = $query->getSingleResult();
		
		$user_apps = array();
		$user_apps[] = $this->user->getPermissionsGroup()->getApplications();
		$user_apps[] = $this->user->getPermissionsGroup()->getAdminApplications();

		foreach ($user_apps as $user_apps_part) {
			foreach ($user_apps_part as $app) {
				if ($app->getId() == $current_app->getId()) {
					$this->application = $app;
				}
			}
		}
		if (!$this->application) {
			throw new \Exception('Permission denied for this application.');
		}
		if (!$this->application->getActivated()) {
			throw new \Exception('This application is not activated. Please contact the administrator to solve the problem');
		}
	}
	
    /*
	 * Check if User is logged to Scube and throw Exception if not
	 */
	private function checkUser()
	{
		$tmp_user = $this->getRequest()->getSession()->get('user');
		
		if (!$tmp_user) {
			throw new \Exception('Applications are connected features, go to login page.');
		}
		$this->user = $this->getDoctrine()->getRepository('ScubeBaseBundle:User')->find($tmp_user->getId());
	}
}

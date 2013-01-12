<?php

namespace Scube\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\Social_Networks;

require_once __DIR__ . '/../../../../vendor/facebook/src/Facebook/Facebook.php';

class FacebookController extends Controller
{
	private $doctrine;
	private $request;
	
	public function indexAction()
	{
		// TEST HERE FACEBOOKCONTROLLER FUNCTIONALITIES :)
		
		return $this->render('ScubeFacebookBundle:Facebook:index.html.twig');
	}
	
	public function postOnUserFeed($facebook, $user_scube_id, $message)
	{
		$repository = $this->doctrine->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('id' => $user_scube_id));
		$user_fb_id = $user->getFbId();
		
		$msg_to_post = array('message' => $message);
		
		$facebook->api($user_fb_id.'/feed', 'post', $msg_to_post);
	}
	
	public function getUserFeed($facebook, $user_scube_id)
	{
		$repository = $this->doctrine->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('id' => $user_scube_id));

		$user_fb_id = $user->getFbId();
																									
		$access_token = $facebook->getAccessToken();
		$fetched_data = $this->fetchUrl("https://graph.facebook.com/".$user_fb_id."/statuses?access_token=".$access_token);
		$feed = json_decode($fetched_data);
		
		return $feed;
	}
	
	// Function to check if User Facebook ID is already registered in DB
	public function checkUserAlreadyRegistered($user_scube_id)
	{
		// Get current user infos
		$repository = $this->doctrine->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('id' => $user_scube_id));
		
		// Check if user already has FB_ID registered in DB
		if ($user->getFbId())
			return true;
		else
			return false;
	}
	
	// Function to register a new Facebook ID for a Scube user
	public function registerFbId($facebook, $user_scube_id)
	{
	
		if ($this->checkUserAlreadyRegistered($user_scube_id))
			return false;
		
		$user_fb_id = $facebook->getUser();
		
		$repository = $this->doctrine->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('id' => $user_scube_id));
		
		$em = $this->doctrine->getEntityManager();
		$user->setFbId($user_fb_id);
		$em->persist($user);
		$em->flush();

		return true;		
	}
	
	// Return URL to call for login on FB a user;
    public function getFbLoginUrl($facebook, $where_to_redirect)
    {
		$parameters = array('scope' => array('publish_stream', 'read_stream', 'offline_access'),
							'redirect_uri'	=> $where_to_redirect);
		
		$url = $facebook->getLoginUrl($parameters);

		return $url;
    }

	// Create new Facebook Object depending on Scube Administrators Settings for Socials Networks
	public function createFacebookObject()
	{
		/*$repository = $this->doctrine->getRepository('ScubeBaseBundle:Social_Networks');
		$fb_settings = $repository->findOneBy(array('name' => 'facebook'));*/
	
		//$appId = '113002988858656';
		//$secret = 'c906e0c740e95395d71a56f3d68dd390';
		
		$appId = $this->doctrine->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "fb_app_id"))->getValue();
		$secret = $this->doctrine->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "fb_secret"))->getValue();
		
	
		$facebook = new \Facebook(array('appId' => $appId,
										'secret' => $secret,
										'cookie' => true));
										
		return $facebook;
	}

	private function fetchUrl($url)
	{
		    $ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_REFERER, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
	}

	public function initController($doctrine, $request) {
		$this->doctrine = $doctrine;
		$this->request = $request;
	}

}

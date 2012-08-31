<?php

namespace Scube\FacebookBundle\Controller;

use Symfony\Component\Form\FormBuilder;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

	var $appId = '348920435183191';
	var $secret = '7ad07da1d7771f1e789ae3ba22c5fe9e';

	public function indexAction(Request $request)
	{
		$facebook = new Facebook(array(
				'appId'  => $this->appId,'cookie'  => true,
				'secret' => $this->secret,
		));

		$formBuilder = $this->createFormBuilder()
		->add('message', 'text');

		$_SESSION['fbUser'] = $facebook->getUser();
		$logoutUrl = '';
		$loginUrl = '';
		$error = '';
		if ($_SESSION['fbUser']) {
			if (!isset($_SESSION['fbAccessToken'])) { //Pour eviter de faire des requetes facebook a chaque fois
				$this->actuFbVariables($request, $facebook);
			}
			$friends = array();
			$friends[$_SESSION['fbProfile']['id']] = 'A poster sur mon propre mur';
			foreach ($_SESSION['fbFriends']['data'] as $key => $value) {
				$friends[$value['id']] = $value['name'];
			}
			$formBuilder->add('to', 'choice', array(
					'choices' => $friends));
		}
		else {
			$_SESSION['fbAccessToken'] = null;
			$_SESSION['fbUser'] = null;
			$_SESSION['fbFriends'] = null;
			$_SESSION['fbFeed'] = null;
			$_SESSION['fbProfile'] = null;
			$_SESSION['fbInbox'] = null;
		}

		$form = $formBuilder->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$error = $this->postToFacebook($form, $_SESSION['fbAccessToken']);
			}
		}

		if ($_SESSION['fbUser']) {
			$logoutUrl = $facebook->getLogoutUrl(array('next' => "http://localhost".$request->getBaseUrl()."/facebook_logout"));
		} else {
			$loginUrl = $facebook->getLoginUrl(array('redirect_uri' => "http://localhost".$request->getBaseUrl()."/facebook",
					'scope' => 'publish_stream, read_stream, read_mailbox'));
		}
		return $this->render('ScubeFacebookBundle:Default:index.html.twig', array('fbProfile' => $_SESSION['fbProfile'],
				'fbFeed' => $_SESSION['fbFeed'],
				'fbFriends' => $_SESSION['fbFriends'],
				'logoutUrl' => $logoutUrl,
				'loginUrl' => $loginUrl,
				'fbUser' => $_SESSION['fbUser'],
				'fbInbox' => $_SESSION['fbInbox'],
				'access_token' => $_SESSION['fbAccessToken'],
				'error' => $error,
				'form' =>$form->createView()));
	}

	public function logoutAction(Request $request)
	{
		$facebook = new Facebook(array(
				'appId'  => $this->appId,
				'secret' => $this->secret,
		));
		unset($_SESSION['fbAccessToken']);
		unset($_SESSION['fbUser']);
		unset($_SESSION['fbFriends']);
		unset($_SESSION['fbFeed']);
		unset($_SESSION['fbProfile']);
		unset($_SESSION['fbInbox']);
		$facebook->destroySession();
		$fbUser = null;
		$error = '';
		$loginUrl = $facebook->getLoginUrl(array('redirect_uri' => "http://localhost".$request->getBaseUrl()."/facebook",
				'scope' => 'publish_stream, read_stream, read_mailbox'));

		return $this->render('ScubeFacebookBundle:Default:index.html.twig', array(	'loginUrl' => $loginUrl,
				'error' => $error,
				'fbUser' => $fbUser));
	}

	public function postToFacebook($form, $access_token)
	{
		$message = $form['message']->getData();
		$ch = curl_init("https://graph.facebook.com/".$form['to']->getData()."/feed");
		$curl_data = array('access_token' => $access_token,
				'message' => $message);
		$options = array(
				CURLOPT_RETURNTRANSFER => true,			// return web page
				CURLOPT_HEADER         => false,		// don't return headers
				CURLOPT_FOLLOWLOCATION => true,			// follow redirects
				CURLOPT_ENCODING       => "",			// handle all encodings
				CURLOPT_USERAGENT      => "scube agent",		// who am i
				CURLOPT_AUTOREFERER    => true,			// set referer on redirect
				CURLOPT_CONNECTTIMEOUT => 120,			// timeout on connect
				CURLOPT_TIMEOUT        => 120,			// timeout on response
				CURLOPT_MAXREDIRS      => 10,			// stop after 10 redirects
				CURLOPT_POST           => 1,			// sending post data
				CURLOPT_POSTFIELDS     => $curl_data,	// post vars
				CURLOPT_SSL_VERIFYHOST => 0,			// don't verify ssl
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_VERBOSE        => 1
		);
		curl_setopt_array($ch,$options);
		$result = curl_exec($ch);
		$_SESSION['fbFeed'] = $this->getFeed();
		return $result;
	}

	public function actuFbVariables(Request $request, $facebook) {
		try {
			if (isset($_GET['code'])) {
				$_SESSION['fbProfile'] = $facebook->api('/me');
				$code = $_GET['code'];
				$token_url = "https://graph.facebook.com/oauth/access_token?"
				. "client_id=" . $this->appId . "&redirect_uri=" . "http://localhost".$request->getBaseUrl()."/facebook"
				. "&client_secret=" . $this->secret . "&code=" . $code;
				$response = file_get_contents($token_url);
				$params = null;
				parse_str($response, $params);
				$_SESSION['fbAccessToken'] = $params['access_token'];
			}
			$fbAccessToken = $_SESSION['fbAccessToken'];

			// GRAPH API
			// liste des infos disponibles -> https://developers.facebook.com/docs/reference/api/
			$_SESSION['fbFeed'] = $this->getFeed();
			$_SESSION['fbFriends'] = $this->getFriends();
			$_SESSION['fbInbox'] = $this->getInbox();
			// GRAPH API END
				
		} catch (FacebookApiException $e) {
			error_log($e);
			$_SESSION['fbUser'] = null;
		}
	}

	public function getFeed() {
		if ($_SESSION['fbAccessToken']) {
			return json_decode(file_get_contents('https://graph.facebook.com/me/feed?access_token='.$_SESSION['fbAccessToken']), true);
		}
		return null;
	}

	public function getInbox() {
		if ($_SESSION['fbAccessToken']) {
			return json_decode(file_get_contents('https://graph.facebook.com/me/inbox?access_token='.$_SESSION['fbAccessToken']), true);
		}
		return null;
	}

	public function getFriends() {
		if ($_SESSION['fbAccessToken']) {
			return json_decode(file_get_contents('https://graph.facebook.com/me/friends?access_token='.$_SESSION['fbAccessToken']), true);
		}
		return null;
	}
}

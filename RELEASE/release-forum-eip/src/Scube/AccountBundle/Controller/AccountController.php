<?php

namespace Scube\AccountBundle\Controller;

use Scube\CoreBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;

class AccountController extends CoreController
{
    /* Account Edition Form */
	public function editAccountAction(Request $request)
    {
    	$this->preprocessApplication();

		$session = $this->getRequest()->getSession();
		
		$user = $this->user;
		
		$form = $this->createFormBuilder($user)
            ->add('Firstname', 'text')
            ->add('Surname', 'text')
			->add('Birthday', 'birthday')
			->add('Gender', 'choice', array('choices' => array('male' => 'Male', 'female' => 'Female')))
			->add('Locale', 'choice', array('choices' => array('en' => 'English', 'fr' => 'French'), 'label' => "Language"))
            ->getForm();
			
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
			
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();
				
				$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
				$LoggingUser = $form->getData();
				$user = $repository->findOneBy(array('email' => $LoggingUser->getEmail(), 'password' => $LoggingUser->getPassword()));
				if ($user)
				{
					$session->set('user', $user);
				}
				if (\Scube\BaseBundle\Controller\BaseController::isMobile())
					return $this->render('ScubeBaseBundle:Base_Mobile:edit_account.html.twig', array('form' => $form->createView(), "success"=>true));
				return $this->render('ScubeAccountBundle:Account:edit_account.html.twig', array('form' => $form->createView(), "success"=>true));
			}
		}
		if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:edit_account.html.twig', array('form' => $form->createView(), "success"=>false));	
		return $this->render('ScubeAccountBundle:Account:edit_account.html.twig', array('form' => $form->createView(), "success"=>false));
    }
	
	/* Email and password Edition Form */
	public function editEmailPasswordAction(Request $request)
    {
    	$this->preprocessApplication();
		$session = $this->getRequest()->getSession();
		
		$user = $this->user;
		
		$form = $this->createFormBuilder($user)
			->add('Email', 'email')
			->add('Password', 'password')
            ->getForm();
			
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
			
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();
				
				$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
				$LoggingUser = $form->getData();
				$user = $repository->findOneBy(array('email' => $LoggingUser->getEmail(), 'password' => $LoggingUser->getPassword()));
				if ($user)
				{
					$session->set('user', $user);
				}
				return $this->render('ScubeAccountBundle:Account:edit_email_password.html.twig', array('form' => $form->createView(), "success"=>true));
			}
		}
		return $this->render('ScubeAccountBundle:Account:edit_email_password.html.twig', array('form' => $form->createView(), "success"=>false));
    }
	
	/* Profile Edition Form */
	public function editProfileAction(Request $request)
    {
    	$this->preprocessApplication();
		$session = $this->getRequest()->getSession();
		
		$user = $this->user;
		
		$profile = $user->getProfile();
		
		$form = $this->createFormBuilder($profile)
			->add('status', 'choice', array("required"=>false, 'choices' => array('single' => 'Single', 'married' => 'Married')))
			//->add('language', 'choice', array("required"=>false, 'choices' => array('en' => 'English', 'fr' => 'Francais')))
			->add('phone_number', 'text', array("required"=>false))
			->add('native_city', 'text', array("required"=>false))
			->add('city', 'text', array("required"=>false))
			->add('address', 'text', array("required"=>false))
			->add('postal_code', 'text', array("required"=>false))
			->add('website', 'url', array("required"=>false))
            ->getForm();
			
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();
				if (\Scube\BaseBundle\Controller\BaseController::isMobile())
					return $this->render('ScubeBaseBundle:Base_Mobile:edit_profile.html.twig', array('form' => $form->createView(),"success"=>true));
				return $this->render('ScubeAccountBundle:Account:edit_profile.html.twig', array('form' => $form->createView(),"success"=>true));
			}
		}
		if (\Scube\BaseBundle\Controller\BaseController::isMobile())
			return $this->render('ScubeBaseBundle:Base_Mobile:edit_profile.html.twig', array('form' => $form->createView(), "success"=>false));	
		return $this->render('ScubeAccountBundle:Account:edit_profile.html.twig', array('form' => $form->createView(), "success"=>false));
    }
	
	/* Email and password Edition Form */
	public function editPictureAction(Request $request)
    {
    	$this->preprocessApplication();
		$session = $this->getRequest()->getSession();
		
		$default_picture = $this->user->getProfile()->getPicture();
		$user = $this->user;
		
		$profile = $user->getProfile();

		$parameters = array();
		$parameters['time'] = time(); // Args for the image to dismiss web browser cache
		$parameters['error'] = false;
		$parameters['error_msg'] = "";
		$parameters['success'] = false;
		$parameters['crop_needed'] = false;
		$parameters['user'] = $user;
		$parameters['image_width'] = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "profile_picture_width"))->getValue();
		$parameters['image_height'] = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "profile_picture_height"))->getValue();

		$path_destination = \Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user);
		$folder_pics = "/profile_pics";

		/* Check if temporary file exists for cropping */
		if ($tmp_array = glob($path_destination.$folder_pics."/tmp.*")) {
			$tmp = basename(current($tmp_array));
			$parameters['crop_needed'] = true;
			$parameters['crop_url'] = $this->get('request')->getBasePath() . "/" . \Scube\BaseBundle\Controller\BaseController::getUserDirectoryPath($user) . $folder_pics . "/".$tmp;
		}
		
		$form = $this->createFormBuilder($profile)
			->add('picture', 'file')
            ->getForm();
        $parameters['form'] = $form->createView();

        $crop_data = array('x'=>'', 'y'=>'', 'h'=>'', 'w'=>'');
        $form_crop = $this->createFormBuilder($crop_data)
			->add('x', 'hidden')
			->add('y', 'hidden')
			->add('h', 'hidden')
			->add('w', 'hidden')
            ->getForm();
        $parameters['form_crop'] = $form_crop->createView();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$form_crop->bindRequest($request);
			
			if ($form_crop->isValid()) {
				$dimensions = $form_crop->getData();
				$this->cropPicture($path_destination.$folder_pics.'/'.basename($parameters['crop_url']),$path_destination.$folder_pics.'/'.str_replace("tmp.", "", basename($parameters['crop_url'])), $dimensions['x'], $dimensions['y'], $dimensions['w'], $dimensions['h']);
				$this->resizePicture($path_destination.$folder_pics.'/'.str_replace("tmp.", "", basename($parameters['crop_url'])));
				$em = $this->getDoctrine()->getEntityManager();
				$profile->setPicture($this->get('request')->getBasePath() . "/" . \Scube\BaseBundle\Controller\BaseController::getUserDirectoryPath($user) . $folder_pics . "/".str_replace("tmp.", "", basename($parameters['crop_url'])));
				$em->flush();
				return $this->redirect($this->generateUrl('ScubeAccountBundle_edit_picture'));
			}

			if ($form->isValid()) {

				$parameters['form'] = $form->createView();
				
				if ($path_destination)
				{
					$path_destination = $path_destination . $folder_pics;
					if (!file_exists($path_destination) || !is_dir($path_destination))
						mkdir($path_destination);
				}
				else 
				{
					$user->getProfile()->setPicture($default_picture);
					$parameters['user'] = $user;
					return $this->render('ScubeAccountBundle:Account:edit_picture.html.twig', $parameters);
				}
				
				if (!$form['picture']->getData()->getSize()) {
					$parameters['error'] = true;
					$parameters['error_msg'] = "Accepted extensions are jpg, png, gif and bmp.";
					$user->getProfile()->setPicture($default_picture);
					$parameters['user'] = $user;
					return $this->render('ScubeAccountBundle:Account:edit_picture.html.twig', $parameters);
				}

				$authorised_extensions = array('png', 'jpg', 'jpeg', 'bmp', 'gif');
				$extension = strtolower($form['picture']->getData()->guessExtension());

				if (!in_array($extension, $authorised_extensions)) {
					$parameters['error'] = true;
					$parameters['error_msg'] = "Accepted extensions are jpg, png, gif and bmp.";
					$user->getProfile()->setPicture($default_picture);
					$parameters['user'] = $user;
					return $this->render('ScubeAccountBundle:Account:edit_picture.html.twig', $parameters);
				}

				$final_filename = $user->getId().'.'.$extension;
				$tmp_filename = "tmp.".$final_filename;
				
				$form['picture']->getData()->move($path_destination, $tmp_filename);

				$full_path = $path_destination.'/'.$tmp_filename;

				/* Check if the image has minimum dimensions required */
				$min_width = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "profile_picture_width"))->getValue();
				$min_height = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "profile_picture_height"))->getValue();
				list($width, $height) = getimagesize($full_path);
				if ($width < $min_width || $height < $min_height) {
					$parameters['error'] = true;
					$parameters['error_msg'] = $this->get('translator')->trans("Minimum dimension is").' '.$min_width.'x'.$min_height;
					$user->getProfile()->setPicture($default_picture);
					$parameters['user'] = $user;
					unlink($full_path);
					return $this->render('ScubeAccountBundle:Account:edit_picture.html.twig', $parameters);
				}

				if (!$this->checkIfPictureNeedCrop($full_path)) {
					rename($full_path, $path_destination.'/'.$final_filename);
					$parameters['crop_needed'] = false;		
				}
				else {
					$parameters['crop_needed'] = true;
					$parameters['crop_url'] = $this->get('request')->getBasePath() . "/" . \Scube\BaseBundle\Controller\BaseController::getUserDirectoryPath($user) . $folder_pics . "/".$tmp_filename;
					$user->getProfile()->setPicture($default_picture);
				}

				if (!$parameters['crop_needed']) {
					$this->resizePicture($path_destination.'/'.$final_filename);
					$em = $this->getDoctrine()->getEntityManager();
					$profile->setPicture($this->get('request')->getBasePath() . "/" . \Scube\BaseBundle\Controller\BaseController::getUserDirectoryPath($user) . $folder_pics . "/".$final_filename);
					$em->flush();
				}
				$parameters['success'] = true;
				$parameters['user'] = $user;

				return $this->render('ScubeAccountBundle:Account:edit_picture.html.twig', $parameters);
			}
		}
		return $this->render('ScubeAccountBundle:Account:edit_picture.html.twig', $parameters);
    }

    /*
     * Check if picture need to be cropped
     */
    private function checkIfPictureNeedCrop($path)
    {
    	$min_width = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "profile_picture_width"))->getValue();
		$min_height = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "profile_picture_height"))->getValue();

    	list($width, $height) = getimagesize($path);
    	$ratio_default = $min_width / $min_height;
    	$ratio = $width / $height;

    	if ($ratio == $ratio_default)
    		return false;
    	return true;
    }

    /*
     * Crop the picture $path depending on arguments
     */
    private function cropPicture($path_src, $path_dest, $x, $y, $w, $h)
    {
    	$dimension = getimagesize($path_src);
    	$img_src = $this->getImage($path_src);

		$x = intval($x);
		$y = intval($y);
		$w = intval($w);
		$h = intval($h);

		if (function_exists('imagecreatetruecolor') && ($img_dest = imagecreatetruecolor($w, $h)))
		{
			/* Preserve Alpha */
			$type = strtolower(substr(strrchr($path_dest,"."),1));
			if($type == "gif" or $type == "png"){
			    imagecolortransparent($img_dest, imagecolorallocatealpha($img_dest, 0, 0, 0, 127));
			    imagealphablending($img_dest, false);
			    imagesavealpha($img_dest, true);
			}

		    imagecopyresampled($img_dest, $img_src, 0, 0, $x, $y, $w, $h, $w, $h);
		}
		else
		{
		    $img_dest = imagecreate($w, $h);
		    imagecopyresized($img_dest, $img_src, 0, 0, $x, $y, $w, $h, $w, $h);
		}

		$this->createImage($img_dest, $path_dest);

		unlink($path_src);
    }

    /*
     * Resize the picture $path depending on settings width and height
     */
    private function resizePicture($path_src)
    {
    	$dimension = getimagesize($path_src);
    	$img_src = $this->getImage($path_src);

		$w = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "profile_picture_width"))->getValue();
		$h = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "profile_picture_height"))->getValue();

		if (function_exists('imagecreatetruecolor') && ($img_dest = imagecreatetruecolor($w, $h)))
		{
			/* Preserve Alpha */
			$type = strtolower(substr(strrchr($path_src,"."),1));
			if($type == "gif" or $type == "png"){
			    imagecolortransparent($img_dest, imagecolorallocatealpha($img_dest, 0, 0, 0, 127));
			    imagealphablending($img_dest, false);
			    imagesavealpha($img_dest, true);
			}

		    imagecopyresampled($img_dest, $img_src, 0, 0, 0, 0, $w, $h, $dimension[0], $dimension[1]);
		}
		else
		{
		    $img_dest = imagecreate($w, $h);
		    imagecopyresized($img_dest, $img_src, 0, 0, $x, $y, $w, $h, $dimension[0], $dimension[1]);
		}

		$this->createImage($img_dest, $path_src);
    }

    /*
     * Get image handler
     */
    private function getImage($path)
    {
    	$type = strtolower(substr(strrchr($path,"."),1));
    	if($type == 'jpeg') $type = 'jpg';
	  	switch($type){
	    	case 'bmp': $img = imagecreatefromwbmp($path); break;
	    	case 'gif': $img = imagecreatefromgif($path); break;
	    	case 'jpg': $img = imagecreatefromjpeg($path); break;
	    	case 'png': $img = imagecreatefrompng($path); break;
	    	default : throw new \Exception('Image not supported');
		}
		return $img;
    }

    /*
     * Create image from handler
     */
    private function createImage($handler, $path)
    {
    	$type = strtolower(substr(strrchr($path,"."),1));
    	if($type == 'jpeg') $type = 'jpg';
	  	switch($type){
		    case 'bmp': imagewbmp($handler, $path); break;
		    case 'gif': imagegif($handler, $path); break;
		    case 'jpg': imagejpeg($handler, $path); break;
		    case 'png': imagepng($handler, $path); break;
		}
    }
}
<?php

namespace Scube\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\UserProfile;
use Scube\BaseBundle\Entity\BaseInterface;
use Scube\BaseBundle\Entity\Widget;
use Scube\BaseBundle\Entity\InterfaceWidget;
use Scube\BaseBundle\Entity\Calendar;
use Scube\BaseBundle\Entity\Mailbox;

class BaseController extends Controller
{

	/* Function for mobile detection */
	public static function isMobile()
	{
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent,0,4)))
			return true;
		return false;
	}
	
    /* Main Page - Index */
    public function indexAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		/* User logged -> display the index */
		if ($session->get('user'))
		{
			$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
			$user = $repository->findOneBy(array('id' => $session->get('user')->getId(), 'email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
			
			$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting');
			$dashboard_width = $repository->findOneBy(array('key' => "dashboard_cell_width"));
			$dashboard_height = $repository->findOneBy(array('key' => "dashboard_cell_height"));
			if ($this->isMobile())
				return $this->render('ScubeBaseBundle:Base_Mobile:index.html.twig', array('user' => $user));
			return $this->render('ScubeBaseBundle:Base:index.html.twig', array('user' => $user, 'dashboard_width'=>$dashboard_width, 'dashboard_height'=>$dashboard_height));
		}
		/* User not logged -> display login form */
		else
		{
			$allow_registration = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "allow_registration"));
			$blocked = false;
			$error = false;
			$user = new User();
		
			$form = $this->createFormBuilder($user)
				->add('Email', 'email')
				->add('Password', 'password')
				->getForm();
				
			if ($request->getMethod() == 'POST') {
				$form->bindRequest($request);
		
				if ($form->isValid()) {
					
					$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
					$LoggingUser = $form->getData();
					$user = $repository->findOneBy(array('email' => $LoggingUser->getEmail(), 'password' => $LoggingUser->getPassword()));
					if ($user && $user->getBlocked() == false)
					{
						$em = $this->getDoctrine()->getEntityManager();

						/* Re-set user ip */
						$userIp = $this->getRequest()->getClientIp();
						$user->setIp($userIp);

						/* User is now online */
						$user->setOnline(true);
					
						/* Re-set last access date */
						
						$user->setDateLastAccess(new \DateTime());
						
						$em->flush();
						
						$session->set('user', $user);
						return $this->redirect($this->generateUrl('_homepage'));
					}
					else if ($user && $user->getBlocked() == true)
						$blocked = true;
					else
						$error = true;
						
					if ($this->isMobile())
						return $this->render('ScubeBaseBundle:Base_Mobile:login.html.twig', array('allow_registration'=>$allow_registration, 'form' => $form->createView(), 'error' => $error, 'blocked' => $blocked));
					return $this->render('ScubeBaseBundle:Base:login.html.twig', array('allow_registration'=>$allow_registration, 'form' => $form->createView(), 'error' => $error, 'blocked' => $blocked));
				}
			}
			
			if (!$allow_registration || $allow_registration->getValue() == "0")
				$allow_registration = false;
			if ($this->isMobile())
				return $this->render('ScubeBaseBundle:Base_Mobile:login.html.twig', array('allow_registration'=>$allow_registration, 'form' => $form->createView(), 'error' => $error, 'blocked' => $blocked));
			return $this->render('ScubeBaseBundle:Base:login.html.twig', array('allow_registration'=>$allow_registration, 'form' => $form->createView(), 'error' => $error, 'blocked' => $blocked));
		}
    }
	public function logoutAction(Request $request)
    {
    	$session = $this->getRequest()->getSession();
    	$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
    	$user = $repository->findOneBy(array('id' => $session->get('user')->getId(), 'email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));

    	$em = $this->getDoctrine()->getEntityManager();
    	$user->setOnline(false);
    	$em->flush();

		$this->getRequest()->getSession()->remove('user');
		return $this->redirect($this->generateUrl('_homepage'));
    }
	
	public function registerAction(Request $request)
    {
		$allow_registration = $this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "allow_registration"));
		if (!$allow_registration || $allow_registration->getValue() == "0")
		{
			if ($this->isMobile())
				return $this->render('ScubeBaseBundle:Base_Mobile:register.html.twig', array("allow_registration"=>false, "success"=>false));
			return $this->render('ScubeBaseBundle:Base:register.html.twig', array("allow_registration"=>false, "success"=>false));
		}
		
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
				
				$test_email_user = $this->getDoctrine()->getRepository('ScubeBaseBundle:User')->findOneBy(array('email' => $user->getEmail()));
				if ($test_email_user)
				{
					if ($this->isMobile())
						return $this->render('ScubeBaseBundle:Base_Mobile:register.html.twig', array("allow_registration"=>$allow_registration,'form' => $form->createView(),"success"=>false,"error_email"=>true));
					return $this->render('ScubeBaseBundle:Base:register.html.twig', array("allow_registration"=>$allow_registration,'form' => $form->createView(),"success"=>false,"error_email"=>true));
				}
					
				/* Set profile object */
				$profile = new UserProfile();
				/* Set interface object */
				$interface = new BaseInterface();
				/* Set calendar object */
				$calendar = new Calendar();
				/* Set mailbox object */
				$mailbox = new Mailbox();
				
				/* Set group object from database */
				$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:PermissionsGroup');
				$default_group = $repository->findOneBy(array('name' => "default"));
				
				/* Get user IP */
				$userIp = $this->getRequest()->getClientIp();
				
				$user->setOnline(false);
				$user->setBlocked(false);
				$user->setDateRegister(new \DateTime());
				$user->setDateLastAccess(new \DateTime());
				$user->setLocale($this->getDoctrine()->getRepository('ScubeBaseBundle:ScubeSetting')->findOneBy(array('key' => "default_locale"))->getValue());
				$user->setProfile($profile);
				$user->setBaseInterface($interface);
				$user->setPermissionsGroup($default_group);
				$user->setCalendar($calendar);
				$user->setMailbox($mailbox);
				$user->setIp($userIp);
				$user->setMaintenancePermission(false);
				
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($profile);
				$em->persist($interface);
				$em->persist($calendar);
				$em->persist($mailbox);
				$em->persist($user);
				$em->flush();
				
				$this->createUserDirectory($this->get('kernel'), $user);
				
				if ($this->isMobile())
					return $this->render('ScubeBaseBundle:Base_Mobile:register.html.twig', array("allow_registration"=>$allow_registration,"success"=>true));
				return $this->render('ScubeBaseBundle:Base:register.html.twig', array("allow_registration"=>$allow_registration,"success"=>true));
			}
		}
			
		return $this->render('ScubeBaseBundle:Base:register.html.twig', array("allow_registration"=>$allow_registration,'form' => $form->createView(), "success"=>false));
    }
	/* Ajax calls */
	public function frame_profileAction(Request $request)
    {
		$session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));					
		return $this->render('ScubeBaseBundle:Base:core_homebox.html.twig', array('user' => $user));
    }
		
	
	
	/* Core methods */
	public static function createUserDirectory($kernel, $user)
	{
		$filename = $kernel->getRootDir(). '/../web/users/'.$user->getId();
		if (!file_exists($filename) && !is_dir($filename))
			mkdir($filename);
	}
	public static function removeUserDirectory($kernel, $user)
	{
		self::rrmdir($kernel->getRootDir(). '/../web/users/'.$user->getId());
	}
	public static function getUserDirectory($kernel, $user)
	{
		$path = $kernel->getRootDir(). '/../web/users/'.$user->getId();
		if (is_dir($path))
			return $path;
		return false;
	}
	public static function getUserDirectoryPath($user)
	{
		return 'users/'.$user->getId();
	}
	public static function rrmdir($dir) 
	{
		if (is_dir($dir)) { 
		 $objects = scandir($dir); 
		 foreach ($objects as $object) { 
		   if ($object != "." && $object != "..") { 
			 if (filetype($dir."/".$object) == "dir") self::rrmdir($dir."/".$object); else unlink($dir."/".$object); 
		   } 
		 } 
		 reset($objects); 
		 rmdir($dir); 
	   } 
	}
	
	/* Get Duration from DateTime */
	public function createdAgo(\DateTime $dateTime)
    {
        $delta = time() - $dateTime->getTimestamp();
        if ($delta < 0)
            throw new \InvalidArgumentException("createdAgo is unable to handle dates in the future");

        $duration = "";
        if ($delta < 60)
        {
            // Seconds
            $time = $delta;
            $duration = $time . " second" . (($time > 1) ? "s" : "") . " ago";
        }
        else if ($delta <= 3600)
        {
            // Mins
            $time = floor($delta / 60);
            $duration = $time . " minute" . (($time > 1) ? "s" : "") . " ago";
        }
        else if ($delta <= 86400)
        {
            // Hours
            $time = floor($delta / 3600);
            $duration = $time . " hour" . (($time > 1) ? "s" : "") . " ago";
        }
        else
        {
            // Days
            $time = floor($delta / 86400);
            $duration = $time . " day" . (($time > 1) ? "s" : "") . " ago";
        }

        return $duration;
    }
}

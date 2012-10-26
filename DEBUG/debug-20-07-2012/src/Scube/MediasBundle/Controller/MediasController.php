<?php

namespace Scube\MediasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\Media;
use Scube\BaseBundle\Entity\MediaFolder;

class MediasController extends Controller
{
    
    public function indexAction(Request $request, $id = false)
    {
        $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$media_folder = new MediaFolder();
		$media = new Media();
		
		$selected_folder = false;
		if ($id)
			$selected_folder = $this->getDoctrine()->getRepository('ScubeBaseBundle:MediaFolder')->find($id);
		
		$form_folder = $this->createFormBuilder($media_folder)
			->add('name', 'text')
            ->getForm();
		
		$form = $this->createFormBuilder($media)
			->add('name', 'text')
			->add('path', 'file')
            ->getForm();
            
        $form_external_video = $this->createFormBuilder($media)
			->add('name', 'text')
			->add('path', 'text', array('label' => 'URL'))
            ->getForm();

       	if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$form_external_video->bindRequest($request);
			$form_folder->bindRequest($request);

			/* Folder creation */
			if ($form_folder->isValid()) {
				
				$em = $this->getDoctrine()->getEntityManager();
				
				$media_folder->setDate(new \Datetime());
				$user->addMediaFolder($media_folder);
				
				$em->persist($media_folder);
				$em->flush();
				
				return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_external_video' => $form_external_video->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>true));
			}
			
			/* File upload */
			if ($request->get('ext_media') == 'false')
			{
				if ($form->isValid()) {
					$folder = "/medias";
					
					$path_destination = \Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user);
					if ($path_destination)
					{
						$path_destination = $path_destination . $folder;
						if (!file_exists($path_destination) || !is_dir($path_destination))
							mkdir($path_destination);
					}
					else
						return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_external_video' => $form_external_video->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
					
					$extension = $form['path']->getData()->guessExtension();

					switch ($extension) {
						/* Pictures */
						case "jpg": $type="picture"; break;
						case "jpeg": $type="picture"; break;
						case "png": $type="picture"; break;
						/* Videos */
						case "3gp": $type="video"; break;
						/* Docs */
						case "pdf": $type="document"; break;
						/* Music */
						case "mp3": $type="music"; break;
						
						default: return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_external_video' => $form_external_video->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
					}
					
					$final_filename = uniqid().'.'.$extension;
					
					$form['path']->getData()->move($path_destination, $final_filename);
					
					$em = $this->getDoctrine()->getEntityManager();
					
					$media->setPath($this->get('request')->getBasePath() . "/" . \Scube\BaseBundle\Controller\BaseController::getUserDirectoryPath($user) . $folder . "/".$final_filename);
					$media->setDate(new \Datetime());
					$media->setType($type);
					$media->setMediaFolder($selected_folder);
					
					$selected_folder->addMedia($media);
					$em->persist($media);
					$em->flush();
					return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_external_video' => $form_external_video->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>true));
				}
			}
			/* Add external video links */
			elseif ($request->get('ext_media') == 'true')
			{
				if ($form_external_video->isValid())
				{
					$em = $this->getDoctrine()->getEntityManager();
					
					$url = $form_external_video['path']->getData();
	
					$domain = $this->getDomain($url);
					if (!empty($domain) && $this->isValidUrl($url))
					{
						switch ($domain) {
							case "youtube.com": $type="youtube"; break;
							case "dailymotion.com": $type="dailymotion"; break;
							case "vimeo.com": $type="vimeo"; break;
							
							default: return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_external_video' => $form_external_video->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
						}
					}
					else
						return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_external_video' => $form_external_video->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
					$media->setPath($url);
					$media->setDate(new \Datetime());
					$media->setType($type);
					$media->setMediaFolder($selected_folder);
										
					$selected_folder->addMedia($media);
					$em->persist($media);
					$em->flush();
					return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_external_video' => $form_external_video->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>true));
				}
			}
		}
		return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_external_video' => $form_external_video->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
    }

    private function getDomain($url)
    {
    	preg_match('@^(?:http://)?([^/]+)@i', $url, $matches);
		$host = $matches[1];
		preg_match('/[^.]+\.[^.]+$/', $host, $domain);
		if (!empty($domain[0]))
			return $domain[0];
		return '';
    }
    
    private function isValidUrl($url, $getVideoId = false)
    {
    	$domain = $this->getDomain($url);
    	$video_id = '';
    	
		if ($domain == 'youtube.com')
		{
			$params = stristr($url, 'v=');
			parse_str($params, $param_tab);
			if (!empty($param_tab['v']))
				$video_id = $param_tab['v'];
		}
		elseif ($domain == 'dailymotion.com')
		{
			if (strpos($url, '_'))
				$video_id = strtok(basename($url), '_');
		}
		elseif ($domain == 'vimeo.com')
		{
			$tmp = basename($url);
			if (ctype_digit($tmp))
				$video_id = $tmp;
		}
		
		if (!empty($video_id) && $getVideoId)
			return $video_id;
    	if (!empty($video_id))
			return true;
			
    	return false;
    }
    
    private function buildEmbeddedUrl($media)
    {
		$url = $media->getPath();
		$type = $media->getType();
		$built_url = '';
		$video_id = $this->isValidUrl($url, true);
		if (!empty($video_id))
		{
			if ($type == 'youtube')
			{
				$built_url = '<iframe width="640" height="360"
							src="http://www.youtube.com/embed/'.$video_id.'?autoplay=1&rel=0"
							frameborder="0" allowfullscreen></iframe>';
			}
			elseif ($type == 'dailymotion')
			{
				$built_url = '<iframe width="640" height="360"
							src="http://www.dailymotion.com/embed/video/'.$video_id.'?logo=0&autoPlay=1&related=0"
							frameborder="0"></iframe>';
			}
			elseif ($type == 'vimeo')
			{
				$built_url = '<iframe width="640" height="360"
						src="http://player.vimeo.com/video/'.$video_id.'?autoplay=1"
						frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
			}
		}
		else
			$built_url = '<p style="color:white">No valid video...</p>';
		return $built_url;
    }
    
	public function loadAction(Request $request, $id)
    {
       $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:Media');
		$media = $repository->find($id);
		$type = $media->getType();
		$folderId = $media->getMediaFolder()->getId();
		//On recupere tous les medias presents dans le repertoire
		$media_list = $repository->findBy(array('media_folder'=>$folderId), array('id'=>'asc'));
		$list_size = count($media_list);
		$prev_media['id'] = '';
		$next_media['id'] = '';
		
		$embedded_url = '';
		if ($type == 'youtube' || $type == 'dailymotion' || $type == 'vimeo')
			$embedded_url = $this->buildEmbeddedUrl($media);
			
		//On recupere le media d'avant et d'apres
		for ($i = 0; $i < $list_size; ++$i)
		{
			if ($media_list[$i]->getId() == $media->getId())
			{
				if ($i > 0)
				{
					$prev_media['id'] = $media_list[$i - 1]->getId();
					$prev_media['type'] = $media_list[$i - 1]->getType();
				}
				if ($i + 1 < $list_size)
				{
					$next_media['id'] = $media_list[$i + 1]->getId();
					$next_media['type'] = $media_list[$i + 1]->getType();
				}
				break ;
			}
		}
		
		return $this->render('ScubeMediasBundle:Medias:load.html.twig', array('media'=>$media, 'prev_media'=>$prev_media, 'next_media'=>$next_media, 'embedded_url'=>$embedded_url));
    }
	
	public function deleteFolderAction(Request $request, $id)
    {
       $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$folder = $this->getDoctrine()->getRepository('ScubeBaseBundle:MediaFolder')->find($id);
		
		$em = $this->getDoctrine()->getEntityManager();
		
		foreach ($folder->getMediasFolder() as $media) {
			unlink(\Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user). "/medias/".basename($media->getPath()));
			$em->remove($media);
		}
		$em->remove($folder);
		$em->flush();
		return $this->redirect($this->generateUrl('ScubeMediasBundle_homepage'));
    }
	
	public function deleteMediaAction(Request $request, $id)
    {
       $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		
		$media = $this->getDoctrine()->getRepository('ScubeBaseBundle:Media')->find($id);
		$folder = $media->getMediaFolder();
		
		$em = $this->getDoctrine()->getEntityManager();
		$type = $media->getType();
		if ($type == 'picture' || $type == 'video' || $type == 'document')
			unlink(\Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user). "/medias/".basename($media->getPath()));
		$em->remove($media);
		$em->flush();
		return $this->redirect($this->generateUrl('ScubeMediasBundle_homepage_folder', array('id'=>$folder->getId())));
    }
}

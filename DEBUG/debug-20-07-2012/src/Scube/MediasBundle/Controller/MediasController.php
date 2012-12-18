<?php

namespace Scube\MediasBundle\Controller;

use Scube\CoreBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\Media;
use Scube\BaseBundle\Entity\MediaFolder;

class MediasController extends CoreController
{
    
    public function indexAction(Request $request)
    {
    	$this->preprocessApplication();

        $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $this->user;
		
		$media_folder = new MediaFolder();
		$form_folder = $this->getCreateFolderForm($media_folder);

		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery("SELECT m FROM ScubeBaseBundle:Media m ORDER BY m.date DESC");
		$medias_result = $query->getResult();
		$own_medias = array();
		if ($medias_result) {
			foreach ($medias_result as $k=>$m) {
				if ($m->getMediaFolder()->getOwner()->getId() == $this->user->getId()) {
					$own_medias[] = $m;
				}
			}
		}

		return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form_folder' => $form_folder->createView(), 'last_medias' => $own_medias));
    }
    
	public function loadAction(Request $request, $id)
    {
    	$this->preprocessApplication();

        $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $this->user;
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:Media');
		$media = $repository->find($id);

		$this->checkUserPermissions($media->getMediaFolder());

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
		$baseUrl = $this->get('request')->getBaseUrl();
		return $this->render('ScubeMediasBundle:Medias:load.html.twig', array('media'=>$media, 'prev_media'=>$prev_media, 'next_media'=>$next_media, 'embedded_url'=>$embedded_url, 'base_url'=>$baseUrl));
    }
	public function deleteMediaAction(Request $request, $id)
    {
    	$this->preprocessApplication();
        $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $this->user;
		
		
		$media = $this->getDoctrine()->getRepository('ScubeBaseBundle:Media')->find($id);
		$folder = $media->getMediaFolder();

		$this->checkUserPermissions($folder);
		
		$em = $this->getDoctrine()->getEntityManager();
		$type = $media->getType();
		if ($type == 'picture' || $type == 'video' || $type == 'document')
			unlink(\Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user). "/medias/".basename($media->getPath()));
		$em->remove($media);
		$em->flush();
		return $this->redirect($this->generateUrl('ScubeMediasBundle_homepage'));
    }

    /* Actions for Folder */
    public function createFolderAction(Request $request)
    {
       $this->preprocessApplication();

       $media_folder = new MediaFolder();
       $form = $this->getCreateFolderForm($media_folder);

       if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			/* Folder creation */
			if ($form->isValid()) {
				
				$em = $this->getDoctrine()->getEntityManager();
				
				$media_folder->setDate(new \Datetime());
				$media_folder->setOwner($this->user);
				$this->user->addMediaFolder($media_folder);
				
				$em->persist($media_folder);
				$em->flush();
			}
		}
		return $this->redirect($this->generateUrl('ScubeMediasBundle_homepage'));
    }
    public function editFolderAction(Request $request, $id)
    {
    	$this->preprocessApplication();

		$folder = $this->getDoctrine()->getRepository('ScubeBaseBundle:MediaFolder')->find($id);

		if (!$folder) {
			throw $this->createNotFoundException('No folder found for id '.$id);
		}
		$this->checkUserPermissions($folder);

		$media_folder = new MediaFolder();
		$form_folder = $this->createFormBuilder($media_folder)
			->add('name', 'text')
            ->getForm();

		return $this->render('ScubeMediasBundle:Medias:edit_folder.html.twig', array('folder'=>$folder, 'form_folder' => $form_folder->createView(), 'user' => $this->user));
    }
    public function deleteFolderAction(Request $request, $id)
    {
    	$this->preprocessApplication();

        $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $this->user;

		$folder = $this->getDoctrine()->getRepository('ScubeBaseBundle:MediaFolder')->find($id);
		if (!$folder) {
			throw $this->createNotFoundException('No folder found for id '.$id);
		}

		$this->checkUserPermissions($folder);

		$em = $this->getDoctrine()->getEntityManager();

		foreach ($folder->getMediasFolder() as $media) {
			$type = $media->getType();
			if ($type == 'picture' || $type == 'video' || $type == 'document')
				unlink(\Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user). "/medias/".basename($media->getPath()));
			$em->remove($media);
		}
		$em->remove($folder);
		$em->flush();
		return $this->redirect($this->generateUrl('ScubeMediasBundle_homepage'));
    }
    public function uploadAction(Request $request, $id)
    {
    	$this->preprocessApplication();
    	
    	$parameters = array();

		$folder = $this->getDoctrine()->getRepository('ScubeBaseBundle:MediaFolder')->find($id);

		if (!$folder) {
			throw $this->createNotFoundException('No folder found for id '.$id);
		}
		$this->checkUserPermissions($folder);

		$parameters['folder'] = $folder;

		$user = $this->user;

		$parameters['user'] = $user;

		$media = new Media();
		$form_external_video = $this->createFormBuilder($media)
			->add('name', 'text')
			->add('path', 'text', array('label' => 'URL'))
            ->getForm();
        $form = $this->createFormBuilder($media)
			->add('name', 'text')
			->add('path', 'file', array('label' => 'File'))
            ->getForm();
        $media_folder = new MediaFolder();
		$form_folder = $this->getCreateFolderForm($media_folder);

        $parameters['form_external_video'] = $form_external_video->createView();
        $parameters['form'] = $form->createView();
        $parameters['form_folder'] = $form_folder->createView();

        $parameters['success'] = false;

        
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$form_external_video->bindRequest($request);
			
			/* File upload */
			if ($request->get('ext_media') == 'false')
			{
				if ($form->isValid()) {
					$folder_name = "/medias";
					
					$path_destination = \Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user);
					if ($path_destination)
					{
						$path_destination = $path_destination . $folder_name;
						if (!file_exists($path_destination) || !is_dir($path_destination))
							mkdir($path_destination);
					}
					else
						return $this->render('ScubeMediasBundle:Medias:upload.html.twig', $parameters);
					
					$extension = $form['path']->getData()->guessExtension();

					switch ($extension) {
						/* Pictures */
						case "jpg": $type="picture"; break;
						case "jpeg": $type="picture"; break;
						case "png": $type="picture"; break;
						/* Videos */
						//case "3gp": $type="video"; break;
						/* Docs */
						case "pdf": $type="document"; break;
						/* Music */
						case "mp3": $type="music"; break;
						
						default: return $this->render('ScubeMediasBundle:Medias:upload.html.twig', $parameters);
					}
					
					$final_filename = uniqid(rand()).'.'.$extension;
					
					$form['path']->getData()->move($path_destination, $final_filename);
					
					$em = $this->getDoctrine()->getEntityManager();
					
					$media->setPath($this->get('request')->getBasePath() . "/" . \Scube\BaseBundle\Controller\BaseController::getUserDirectoryPath($user) . $folder_name . "/".$final_filename);
					$media->setDate(new \Datetime());
					$media->setType($type);
					$media->setMediaFolder($folder);
					
					$folder->addMedia($media);
					$em->persist($media);
					$em->flush();

					$parameters['success'] = true;

					return $this->render('ScubeMediasBundle:Medias:upload.html.twig', $parameters);
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
							
							default: return $this->render('ScubeMediasBundle:Medias:upload.html.twig', $parameters);
						}
					}
					else
						return $this->render('ScubeMediasBundle:Medias:upload.html.twig', $parameters);
					$media->setPath($url);
					$media->setDate(new \Datetime());
					$media->setType($type);
					$media->setMediaFolder($folder);
										
					$folder->addMedia($media);
					$em->persist($media);
					$em->flush();

					$parameters['success'] = true;
					return $this->render('ScubeMediasBundle:Medias:upload.html.twig', $parameters);
				}
			}
		}
		return $this->render('ScubeMediasBundle:Medias:upload.html.twig', $parameters);
    }

    /*
     * Get needed variables for template medias_core
     */
    private function getCreateFolderForm($media_folder) {
    	$form_folder = $this->createFormBuilder($media_folder)
			->add('name', 'text')
            ->getForm();

        return $form_folder;
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

    private function checkUserPermissions($folder) {
    	$permission = false;

    	foreach ($this->user->getMediaFolders() as $f) {
    		if ($folder->getId() == $f->getId()) {
    			$permission = true;
    			break;
    		}
    	}

    	if ($permission == false) {
    		throw new \Exception('Permission denied for this folder');
    	}
    }

}

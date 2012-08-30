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
			
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$form_folder->bindRequest($request);
			/* Folder creation */
			if ($form_folder->isValid()) {
				
				
				
				$em = $this->getDoctrine()->getEntityManager();
				
				$media_folder->setDate(new \Datetime());
				$user->addMediaFolder($media_folder);
				
				$em->persist($media_folder);
				$em->flush();
				
				return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>true));
			}
			
			/* File upload */
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
					return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
				
				$extension = $form['path']->getData()->guessExtension();
				
				switch ($extension) {
					/* Pictures */
					case "jpg": $type="picture"; break;
					case "jpeg": $type="picture"; break;
					case "png": $type="picture"; break;
					/* Videos */
					//case "avi": $type="video"; break;
					/* Docs */
					case "pdf": $type="document"; break;
					
					default: return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
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
				
				return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>true));
			}
		}
		return $this->render('ScubeMediasBundle:Medias:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
    }
	
	public function loadAction(Request $request, $id)
    {
       $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$media = $this->getDoctrine()->getRepository('ScubeBaseBundle:Media')->find($id);
		return $this->render('ScubeMediasBundle:Medias:load.html.twig', array('media'=>$media));
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
		
		unlink(\Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user). "/medias/".basename($media->getPath()));
		$em->remove($media);
		$em->flush();
		return $this->redirect($this->generateUrl('ScubeMediasBundle_homepage_folder', array('id'=>$folder->getId())));
    }
}

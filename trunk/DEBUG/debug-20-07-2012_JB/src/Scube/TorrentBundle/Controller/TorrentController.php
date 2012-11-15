<?php

namespace Scube\TorrentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Scube\BaseBundle\Entity\User;
use Scube\BaseBundle\Entity\Torrent;
use Scube\BaseBundle\Entity\TorrentFolder;


class TorrentController extends Controller
{
    
    public function indexAction(Request $request, $id = false)
    {
        $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array( 'email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$torrent_folder = new TorrentFolder();
		$torrent = new Torrent();
		
		$selected_folder = false;
		if ($id)
			$selected_folder = $this->getDoctrine()->getRepository('ScubeBaseBundle:TorrentFolder')->find($id);
		
		$form_folder = $this->createFormBuilder($torrent_folder)
			->add('name', 'text')
            ->getForm();
		
		$form = $this->createFormBuilder($torrent)
			->add('name', 'text')
			->add('path', 'file')
            ->getForm();

       	if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$form_folder->bindRequest($request);

			/* Folder creation */
			if ($form_folder->isValid()) {
				
				$em = $this->getDoctrine()->getEntityManager();
				
				$torrent_folder->setDate(new \Datetime());
				$user->addTorrentFolder($torrent_folder);
				
				$em->persist($torrent_folder);
				$em->flush();
				
				return $this->render('ScubeTorrentBundle:Torrent:index.html.twig', array('error' => null,'user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>true));
			}
			
			/* File upload */
			if ($request->get('ext_torrent') == 'false')
			{
				if ($form->isValid()) {
					$folder = "/torrent";
					
					$path_destination = \Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user);
					if ($path_destination)
					{
						$path_destination = $path_destination . $folder;
						if (!file_exists($path_destination) || !is_dir($path_destination))
							mkdir($path_destination);
					}
					else
						return $this->render('ScubeTorrentBundle:Torrent:index.html.twig', array('error' => null,'user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
					
					$extension = $form['path']->getData()->guessExtension();

					switch ($extension) {
						/* Torrent (guessExtension isn't working on torrent files) [Work in progress] */
						default: $type="torrent";
						
// 						default: return $this->render('ScubeTorrentBundle:Torrent:index.html.twig', array('user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
					}
					
					$final_filename = uniqid().'.'.$extension;
					
					$form['path']->getData()->move($path_destination, $final_filename);
					
					$em = $this->getDoctrine()->getEntityManager();
					
					$torrent->setPath($this->get('request')->getBasePath() . "/" . \Scube\BaseBundle\Controller\BaseController::getUserDirectoryPath($user) . $folder . "/".$final_filename);
					$torrent->setDate(new \Datetime());
					$torrent->setType($type);
					$torrent->setTorrentFolder($selected_folder);
					
					$selected_folder->addTorrent($torrent);
					$em->persist($torrent);
					$em->flush();
					return $this->render('ScubeTorrentBundle:Torrent:index.html.twig', array('error' => null,'user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>true));
				}
			}
		}
		return $this->render('ScubeTorrentBundle:Torrent:index.html.twig', array('error' => null,'user'=>$user, 'form' => $form->createView(), 'form_folder' => $form_folder->createView(), 'folder'=>$selected_folder, "success"=>false));
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
	
	public function deleteFolderAction(Request $request, $id)
    {
       $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		$folder = $this->getDoctrine()->getRepository('ScubeBaseBundle:TorrentFolder')->find($id);
		
		$em = $this->getDoctrine()->getEntityManager();
		
		foreach ($folder->getTorrentFolder() as $torrent) {
			unlink(\Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user). "/torrent/".basename($torrent->getPath()));
			$em->remove($torrent);
		}
		$em->remove($folder);
		$em->flush();
		return $this->redirect($this->generateUrl('ScubeTorrentBundle_homepage'));
    }
	
	public function deleteTorrentAction(Request $request, $id)
    {
       $session = $this->getRequest()->getSession();
		
		$repository = $this->getDoctrine()->getRepository('ScubeBaseBundle:User');
		$user = $repository->findOneBy(array('email' => $session->get('user')->getEmail(), 'password' => $session->get('user')->getPassword()));
		
		
		$torrent = $this->getDoctrine()->getRepository('ScubeBaseBundle:Torrent')->find($id);
		$folder = $torrent->getTorrentFolder();
		
		$em = $this->getDoctrine()->getEntityManager();
		$type = $torrent->getType();
		if ($type == 'torrent')
			unlink(\Scube\BaseBundle\Controller\BaseController::getUserDirectory($this->get('kernel'), $user). "/torrent/".basename($torrent->getPath()));
		$em->remove($torrent);
		$em->flush();
		return $this->redirect($this->generateUrl('ScubeTorrentBundle_homepage_folder', array('id'=>$folder->getId())));
    }
}

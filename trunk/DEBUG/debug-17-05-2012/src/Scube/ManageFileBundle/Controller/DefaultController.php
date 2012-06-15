<?php

namespace Scube\ManageFileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Scube\ManageFileBundle\Entity\File;

class DefaultController extends Controller
{
    
    public function indexAction($name = "toto")
    {
        return $this->redirect($this->generateUrl('ScubeManageFileBundle_pictures_show'));
    }
    
	public function uploadAction(Request $request)
	{		
		$userID = "1234";
		$picturesPath = "Medias/Pictures/$userID/";
		$pdfPath = "Medias/PDF/$userID/";
		$file = new File();

		$form = $this->createFormBuilder($file)
		->add('file', 'file')
		->getForm();
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$extension = $form['file']->getData()->guessExtension();
				if ($extension == 'pdf')
					$form['file']->getData()->move($pdfPath, rand().'.'.$extension);
				else
 					$form['file']->getData()->move($picturesPath, rand().'.'.$extension);
				return $this->redirect($this->generateUrl('ScubeManageFileBundle_pictures_show'));
			}
		}
		
		return $this->render('ScubeManageFileBundle:Default:upload.html.twig', array('form' => $form->createView()));
	}
	
	public function deleteAction(Request $request)
	{
		if ($request->getMethod() == 'POST') {
			$parameters = $request->request->get('pictures');
			if (count($parameters) >= 1) {
				foreach ($parameters as $picture) {
					$filename = explode('/', $picture);
					$filename = $filename[count($filename) - 2].'/'.$filename[count($filename) - 1];
					unlink('Medias/Pictures/'.$filename);
				}
				return $this->redirect($this->generateUrl('ScubeManageFileBundle_pictures_show'));
			}
		}
		
		$userID = "1234";
		$filePath = "Medias/Pictures/$userID";
		
		$list = array();
		$handle = @dir($filePath);

		$i = 0;
		while ($entry = $handle->read()) {
			if (!@is_dir($entry))
				$list[$i++] = $this->get('request')->getBasePath()."/$filePath/$entry";
		}

		return $this->render('ScubeManageFileBundle:Default:delete.html.twig', array('list' => $list));
	}
	
	public function showAction(Request $request)
	{
		// PICTURES
		
		$userID = "1234";
		$filePath = "Medias/Pictures/$userID";
		
		$list = array();
		$handle = @dir($filePath);

		$i = 0;
		while ($entry = $handle->read()) {
			if (!@is_dir($entry))
				$list[$i++] = $this->get('request')->getBasePath()."/$filePath/$entry";

		}
		return $this->render('ScubeManageFileBundle:Default:show.html.twig', array('list' => $list, 'server_addr' => $_SERVER['HTTP_HOST']));
		
		// PDF
		/*
		$userID = "1234";
		$filePath = "Medias/PDF/$userID";
		
		$list = array();
		$handle = @dir($filePath);

		$i = 0;
		while ($entry = $handle->read()) {
			if (!@is_dir($entry))
				$list[$i++] = $this->get('request')->getBasePath()."/$filePath/$entry";
		}
		return $this->render('ScubeManageFileBundle:Default:pdf.html.twig', array('pdf_path' => $list[0]));
		*/
	}
	
}

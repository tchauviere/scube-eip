<?php

namespace Scube\ManageFileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Scube\ManageFileBundle\Entity\File;

class DefaultController extends Controller
{

	public function indexAction($name = "toto")
	{
		return $this->redirect($this->generateUrl('ScubeManageFileBundle_upload'));
	}

	public function uploadAction(Request $request)
	{
		$userID = "1234";
		$picturesPath = "Medias/Pictures/$userID/";
		$pdfPath = "Medias/PDF/$userID/";
		$videosPath = "Medias/Videos/$userID/";
		$file = new File();

		$form = $this->createFormBuilder($file)
		->add('file', 'file')
		->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$mimeType = $form['file']->getData()->getMimeType();
				$extension = $form['file']->getData()->guessExtension();
				if ($mimeType == 'application/pdf' || $mimeType == 'application/x-pdf') {
					$form['file']->getData()->move($pdfPath, rand().'.'.$extension);
					return $this->redirect($this->generateUrl('ScubeManageFileBundle_show_pdf'));
				}
				else if ($mimeType == 'image/png' || $mimeType == 'image/jpg' || $mimeType == 'image/jpeg') {
					$form['file']->getData()->move($picturesPath, rand().'.'.$extension);
					return $this->redirect($this->generateUrl('ScubeManageFileBundle_show_pictures'));
				}
				else {
					$form['file']->getData()->move($videosPath, rand().'.'.$extension);
					return $this->redirect($this->generateUrl('ScubeManageFileBundle_show_videos'));
				}
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
				return $this->redirect($this->generateUrl('ScubeManageFileBundle_show_pictures'));
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

	public function showPdfAction(Request $request)
	{
		$userID = "1234";
		$filePath = "Medias/PDF/$userID";

		$list = array();
		$list[0] = "";
		$handle = @dir($filePath);

		$i = 0;
		while ($entry = $handle->read()) {
			if (!@is_dir($entry))
				$list[$i++] = $this->get('request')->getBasePath()."/$filePath/$entry";
		}
		return $this->render('ScubeManageFileBundle:Default:pdf.html.twig', array('pdf_path' => $list[0]));
		
	}

	public function showPicturesAction(Request $request)
	{
		$userID = "1234";
		$filePath = "Medias/Pictures/$userID";

		$list = array();
		$handle = @dir($filePath);

		$i = 0;
		while ($entry = $handle->read()) {
		if (!@is_dir($entry))
			$list[$i++] = $this->get('request')->getBasePath()."/$filePath/$entry";
		}
		return $this->render('ScubeManageFileBundle:Default:pictures.html.twig', array('list' => $list, 'server_addr' => $_SERVER['HTTP_HOST']));
	}

	public function showVideosAction(Request $request)
	{
		$userID = "1234";
		$filePath = "Medias/Videos/$userID";

		$list = array();
		$handle = @dir($filePath);

		$i = 0;
		while ($entry = $handle->read()) {
		if (!@is_dir($entry))
			$list[$i++] = $this->get('request')->getBasePath()."/$filePath/$entry";
		}
		return $this->render('ScubeManageFileBundle:Default:videos.html.twig', array('list' => $list));
	}
	
}

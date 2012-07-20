<?php
namespace Scube\ManageFileBundle\Entity;

class Video
{
	protected $file;

	public function getFile()
	{
		return $this->file;
	}
	
	public function setFile($file)
	{
		$this->file = $file;
	}
}
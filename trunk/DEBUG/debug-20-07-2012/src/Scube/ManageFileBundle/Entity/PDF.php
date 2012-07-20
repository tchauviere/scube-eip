<?php
namespace Scube\ManageFileBundle\Entity;

class PDF
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
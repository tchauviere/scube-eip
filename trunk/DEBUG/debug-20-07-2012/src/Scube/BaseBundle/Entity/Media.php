<?php
// src/Scube/BaseBundle/Entity/Media.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="media")
 */
class Media
{
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $name;
	
	/**
     * @ORM\Column(type="string", length=200)
     */
    protected $path;
	
	/**
     * @ORM\Column(type="string", length=20)
     */
    protected $type;
	
    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;
	
	/**
     * @ORM\ManyToOne(targetEntity="Scube\BaseBundle\Entity\MediaFolder")
     * @ORM\JoinColumn(name="media_folder", referencedColumnName="id")
     */
    protected $media_folder;
	
	public function __construct()
    {
    }
	
	/**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set media_folder
     *
     * @param Scube\BaseBundle\Entity\MediaFolder $mediaFolder
     */
    public function setMediaFolder(\Scube\BaseBundle\Entity\MediaFolder $mediaFolder)
    {
        $this->media_folder = $mediaFolder;
    }

    /**
     * Get media_folder
     *
     * @return Scube\BaseBundle\Entity\MediaFolder 
     */
    public function getMediaFolder()
    {
        return $this->media_folder;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
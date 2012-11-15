<?php
// src/Scube/BaseBundle/Entity/Torrent.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="torrent")
 */
class Torrent
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
     * @ORM\ManyToOne(targetEntity="Scube\BaseBundle\Entity\TorrentFolder")
     * @ORM\JoinColumn(name="torrent_folder", referencedColumnName="id")
     */
    protected $torrent_folder;
	
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
     * Set torrent_folder
     *
     * @param Scube\BaseBundle\Entity\TorrentFolder $torrentFolder
     */
    public function setTorrentFolder(\Scube\BaseBundle\Entity\TorrentFolder $torrentFolder)
    {
        $this->torrent_folder = $torrentFolder;
    }

    /**
     * Get torrent_folder
     *
     * @return Scube\BaseBundle\Entity\TorrentFolder 
     */
    public function getTorrentFolder()
    {
        return $this->torrent_folder;
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
<?php
// src/Scube/BaseBundle/Entity/TorrentFolder.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="torrent_folder")
 */
class TorrentFolder
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
     * @ORM\Column(type="datetime")
     */
    protected $date;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\Torrent")
	 * @ORM\JoinTable(name="torrents_folder")
     */ 
    protected $torrents_folder;
	
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
     * Add torrents_folder
     *
     * @param Scube\BaseBundle\Entity\Torrent $torrentsFolder
     */
    public function addTorrent(\Scube\BaseBundle\Entity\Torrent $torrentsFolder)
    {
        $this->torrents_folder[] = $torrentsFolder;
    }

    /**
     * Get torrents_folder
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTorrentFolder()
    {
        return $this->torrents_folder;
    }
}
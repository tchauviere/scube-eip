<?php
// src/Scube/BaseBundle/Entity/MediaFolder.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_folder")
 */
class MediaFolder
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
     * @ORM\ManyToOne(targetEntity="Scube\BaseBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $owner;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\Media")
	 * @ORM\JoinTable(name="medias_folder")
     */ 
    protected $medias_folder;
	
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
     * Add medias_folder
     *
     * @param Scube\BaseBundle\Entity\Media $mediasFolder
     */
    public function addMedia(\Scube\BaseBundle\Entity\Media $mediasFolder)
    {
        $this->medias_folder[] = $mediasFolder;
    }

    /**
     * Get medias_folder
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMediasFolder()
    {
        return $this->medias_folder;
    }

    /**
     * Set owner
     *
     * @param Scube\BaseBundle\Entity\User $owner
     */
    public function setOwner(\Scube\BaseBundle\Entity\User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return Scube\BaseBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
<?php
// src/Scube/BaseBundle/Entity/ConnectionsGroup.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="connections_group")
 */
class ConnectionsGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\User")
	 * @ORM\JoinTable(name="connections_group_users")
     */
    protected $users;
	
	/**
     * @ORM\Column(type="boolean")
     */
    protected $auth_profile_news;
	
	/**
     * @ORM\Column(type="boolean")
     */
    protected $auth_profile_infos;
	
	/**
     * @ORM\Column(type="boolean")
     */
    protected $auth_profile_pics;

	public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add users
     *
     * @param Scube\BaseBundle\Entity\User $users
     */
    public function addUser(\Scube\BaseBundle\Entity\User $users)
    {
        $this->users[] = $users;
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set auth_profile_news
     *
     * @param boolean $authProfileNews
     */
    public function setAuthProfileNews($authProfileNews)
    {
        $this->auth_profile_news = $authProfileNews;
    }

    /**
     * Get auth_profile_news
     *
     * @return boolean 
     */
    public function getAuthProfileNews()
    {
        return $this->auth_profile_news;
    }

    /**
     * Set auth_profile_infos
     *
     * @param boolean $authProfileInfos
     */
    public function setAuthProfileInfos($authProfileInfos)
    {
        $this->auth_profile_infos = $authProfileInfos;
    }

    /**
     * Get auth_profile_infos
     *
     * @return boolean 
     */
    public function getAuthProfileInfos()
    {
        return $this->auth_profile_infos;
    }

    /**
     * Set auth_profile_pics
     *
     * @param boolean $authProfilePics
     */
    public function setAuthProfilePics($authProfilePics)
    {
        $this->auth_profile_pics = $authProfilePics;
    }

    /**
     * Get auth_profile_pics
     *
     * @return boolean 
     */
    public function getAuthProfilePics()
    {
        return $this->auth_profile_pics;
    }
}
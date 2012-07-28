<?php
// src/Scube/BaseBundle/Entity/PermissionsGroup.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="permissions_group")
 */
class PermissionsGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique="true", length=100)
     */
    protected $name;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\Application")
	 * @ORM\JoinTable(name="group_applications")
     */ 
    protected $applications;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\Application")
	 * @ORM\JoinTable(name="group_admin_applications") 
     */ 
    protected $admin_applications;
    public function __construct()
    {
        $this->applications = new \Doctrine\Common\Collections\ArrayCollection();
    $this->admin_applications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add applications
     *
     * @param Scube\BaseBundle\Entity\Application $applications
     */
    public function addApplication(\Scube\BaseBundle\Entity\Application $applications)
    {
        $this->applications[] = $applications;
    }
	
	/**
     * Set applications
     *
     * @param Scube\BaseBundle\Entity\Application $applications
     */
    public function setApplication(\Doctrine\Common\Collections\ArrayCollection $applications)
    {
        $this->applications = $applications;
    }

    /**
     * Get applications
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }
	
	/**
     * Add admin applications
     *
     * @param Scube\BaseBundle\Entity\Application $applications
     */
    public function addAdminApplication(\Scube\BaseBundle\Entity\Application $applications)
    {
        $this->admin_applications[] = $applications;
    }
	
	/**
     * Set admin applications
     *
     * @param Scube\BaseBundle\Entity\Application $applications
     */
    public function setAdminApplication(\Doctrine\Common\Collections\ArrayCollection $applications)
    {
        $this->admin_applications = $applications;
    }

    /**
     * Get admin_applications
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAdminApplications()
    {
        return $this->admin_applications;
    }
}
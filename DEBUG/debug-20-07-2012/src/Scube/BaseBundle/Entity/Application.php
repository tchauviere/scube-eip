<?php
// src/Scube/BaseBundle/Entity/Application.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="application")
 */
class Application
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
	 * @ORM\OneToMany(targetEntity="Scube\BaseBundle\Entity\Widget", mappedBy="application")
	 */
	protected $widgets;
	
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;
	
	/**
     * @ORM\Column(type="string", length=200)
     */
    protected $bundle_name;
	
	/**
     * @ORM\Column(type="string", length=20)
     */
    protected $type;
	
	/**
     * @ORM\Column(type="string", length=200)
     */
    protected $link;
	
	/**
     * @ORM\Column(type="text")
     */
    protected $description;
	
	/**
     * @ORM\Column(type="boolean")
     */
    protected $activated;
	
	/**
     * @ORM\Column(type="boolean")
     */
    protected $necessary;
	
    public function __construct()
    {
        $this->widgets = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set bundle_name
     *
     * @param string $bundleName
     */
    public function setBundleName($bundleName)
    {
        $this->bundle_name = $bundleName;
    }

    /**
     * Get bundle_name
     *
     * @return string 
     */
    public function getBundleName()
    {
        return $this->bundle_name;
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add widgets
     *
     * @param Scube\BaseBundle\Entity\Widget $widgets
     */
    public function addWidget(\Scube\BaseBundle\Entity\Widget $widgets)
    {
        $this->widgets[] = $widgets;
    }

    /**
     * Get widgets
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * Set activated
     *
     * @param boolean $activated
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
    }

    /**
     * Get activated
     *
     * @return boolean 
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Set necessary
     *
     * @param boolean $necessary
     */
    public function setNecessary($necessary)
    {
        $this->necessary = $necessary;
    }

    /**
     * Get necessary
     *
     * @return boolean 
     */
    public function getNecessary()
    {
        return $this->necessary;
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
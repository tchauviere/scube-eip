<?php
// src/Scube/BaseBundle/Entity/Apps.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="apps")
 */
class Apps
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
     * @ORM\Column(type="string", length=200)
     */
    protected $bundleName;
	
	/**
     * @ORM\Column(type="string", length=200)
     */
    protected $link;
	
	/**
     * @ORM\Column(type="text")
     */
    protected $description;

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
     * Set route
     *
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
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
     * Set bundleName
     *
     * @param string $bundleName
     */
    public function setBundleName($bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * Get bundleName
     *
     * @return string 
     */
    public function getBundleName()
    {
        return $this->bundleName;
    }
}
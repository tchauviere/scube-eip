<?php
// src/Scube/BaseBundle/Entity/Widget.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="widget")
 */
class Widget
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Scube\BaseBundle\Entity\Application", inversedBy="widgets")
	 * @ORM\JoinColumn(name="application", referencedColumnName="id", onDelete="CASCADE", onUpdate="CASCADE")
	 */
	protected $application;
	
	/**
     * @ORM\Column(type="string", length="200")
     */
    protected $name;
	
	/**
     * @ORM\Column(type="string", unique="true", length="200")
     */
    protected $bundle_name;
	
	/**
     * @ORM\Column(type="string", length="200")
     */
    protected $link;
	
	/**
     * @ORM\Column(type="string", length="200")
     */
    protected $button_link;
	
	/**
     * @ORM\Column(type="string", length="50")
     */
    protected $type;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $minWidth;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $minHeight;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $maxWidth;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $maxHeight;
	
	/**
     * @ORM\Column(type="boolean")
     */
    protected $fullscreen;

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
     * Set minWidth
     *
     * @param integer $minWidth
     */
    public function setMinWidth($minWidth)
    {
        $this->minWidth = $minWidth;
    }

    /**
     * Get minWidth
     *
     * @return integer 
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }

    /**
     * Set minHeight
     *
     * @param integer $minHeight
     */
    public function setMinHeight($minHeight)
    {
        $this->minHeight = $minHeight;
    }

    /**
     * Get minHeight
     *
     * @return integer 
     */
    public function getMinHeight()
    {
        return $this->minHeight;
    }

    /**
     * Set maxWidth
     *
     * @param integer $maxWidth
     */
    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;
    }

    /**
     * Get maxWidth
     *
     * @return integer 
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * Set maxHeight
     *
     * @param integer $maxHeight
     */
    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;
    }

    /**
     * Get maxHeight
     *
     * @return integer 
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * Set fullscreen
     *
     * @param boolean $fullscreen
     */
    public function setFullscreen($fullscreen)
    {
        $this->fullscreen = $fullscreen;
    }

    /**
     * Get fullscreen
     *
     * @return boolean 
     */
    public function getFullscreen()
    {
        return $this->fullscreen;
    }

    /**
     * Set application
     *
     * @param Scube\BaseBundle\Entity\Application $application
     */
    public function setApplication(\Scube\BaseBundle\Entity\Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get application
     *
     * @return Scube\BaseBundle\Entity\Application 
     */
    public function getApplication()
    {
        return $this->application;
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
     * Set button_link
     *
     * @param string $buttonLink
     */
    public function setButtonLink($buttonLink)
    {
        $this->button_link = $buttonLink;
    }

    /**
     * Get button_link
     *
     * @return string 
     */
    public function getButtonLink()
    {
        return $this->button_link;
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
<?php
// src/Scube/BaseBundle/Entity/Widgets.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="widgets")
 */
class Widgets
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\Apps")
     * @ORM\JoinColumn(name="apps", referencedColumnName="id")
     */
    protected $app;
	
	/**
     * @ORM\Column(type="string", length="200")
     */
    protected $link;
	
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
     * Set width
     *
     * @param integer $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set pos_x
     *
     * @param integer $posX
     */
    public function setPosX($posX)
    {
        $this->pos_x = $posX;
    }

    /**
     * Get pos_x
     *
     * @return integer 
     */
    public function getPosX()
    {
        return $this->pos_x;
    }

    /**
     * Set pos_y
     *
     * @param integer $posY
     */
    public function setPosY($posY)
    {
        $this->pos_y = $posY;
    }

    /**
     * Get pos_y
     *
     * @return integer 
     */
    public function getPosY()
    {
        return $this->pos_y;
    }

    /**
     * Set app
     *
     * @param Scube\BaseBundle\Entity\Apps $app
     */
    public function setApp(\Scube\BaseBundle\Entity\Apps $app)
    {
        $this->app = $app;
    }

    /**
     * Get app
     *
     * @return Scube\BaseBundle\Entity\Apps 
     */
    public function getApp()
    {
        return $this->app;
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
}
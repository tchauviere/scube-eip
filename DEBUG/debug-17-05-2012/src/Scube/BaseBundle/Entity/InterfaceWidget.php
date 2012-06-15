<?php
// src/Scube/BaseBundle/Entity/InterfaceWidget.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="interface_widget")
 */
class InterfaceWidget
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\Widget")
     * @ORM\JoinColumn(name="widget", referencedColumnName="id")
     */
    protected $widget;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $width;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $height;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $pos_x;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $pos_y;

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
     * Set widget
     *
     * @param Scube\BaseBundle\Entity\Widget $widget
     */
    public function setWidget(\Scube\BaseBundle\Entity\Widget $widget)
    {
        $this->widget = $widget;
    }

    /**
     * Get widget
     *
     * @return Scube\BaseBundle\Entity\Widget 
     */
    public function getWidget()
    {
        return $this->widget;
    }
}
<?php
// src/Scube/BaseBundle/Entity/BaseInterface.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="base_interface")
 */
class BaseInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
     * @ORM\OneToMany(targetEntity="Scube\BaseBundle\Entity\InterfaceWidget", mappedBy="widget")
	 * @ORM\JoinTable(name="interface_widgets") 
     */ 
    protected $widgets;
	
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
     * Add widgets
     *
     * @param Scube\BaseBundle\Entity\InterfaceWidget $widgets
     */
    public function addInterfaceWidget(\Scube\BaseBundle\Entity\InterfaceWidget $widgets)
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
}
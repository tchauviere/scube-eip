<?php
// src/Scube/BaseBundle/Entity/Mailbox.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="mailbox")
 */
class Mailbox
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\Conversation")
	 * @ORM\JoinTable(name="conversations")
     */ 
    protected $conversations;
	
	public function __construct()
    {
		$this->conversations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add conversations
     *
     * @param Scube\BaseBundle\Entity\Conversation $conversations
     */
    public function addConversation(\Scube\BaseBundle\Entity\Conversation $conversations)
    {
        $this->conversations[] = $conversations;
    }

    /**
     * Get conversations
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getConversations()
    {
        return $this->conversations;
    }
}
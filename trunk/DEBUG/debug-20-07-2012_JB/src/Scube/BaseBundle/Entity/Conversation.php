<?php
// src/Scube/BaseBundle/Entity/Conversation.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="conversation")
 */
class Conversation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
     * @ORM\ManyToOne(targetEntity="Scube\BaseBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $interlocutor;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\Mail")
	 * @ORM\JoinTable(name="mails")
     */ 
    protected $mails;
	
	public function __construct()
    {
		$this->mails = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set interlocutor
     *
     * @param Scube\BaseBundle\Entity\User $interlocutor
     */
    public function setInterlocutor(\Scube\BaseBundle\Entity\User $interlocutor)
    {
        $this->interlocutor = $interlocutor;
    }

    /**
     * Get interlocutor
     *
     * @return Scube\BaseBundle\Entity\User 
     */
    public function getInterlocutor()
    {
        return $this->interlocutor;
    }

    /**
     * Add mails
     *
     * @param Scube\BaseBundle\Entity\Mail $mails
     */
    public function addMail(\Scube\BaseBundle\Entity\Mail $mails)
    {
        $this->mails[] = $mails;
    }

    /**
     * Get mails
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMails()
    {
        return $this->mails;
    }
}
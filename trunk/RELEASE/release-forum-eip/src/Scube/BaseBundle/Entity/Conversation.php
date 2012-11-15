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
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\User")
     * @ORM\JoinColumn(name="user")
     */
    protected $recipients;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $new_mails;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_last_mail;
	
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

    /**
     * Add recipients
     *
     * @param Scube\BaseBundle\Entity\User $recipients
     */
    public function addUser(\Scube\BaseBundle\Entity\User $recipients)
    {
        $this->recipients[] = $recipients;
    }

    /**
     * Get recipients
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Set new_mails
     *
     * @param boolean $newMails
     */
    public function setNewMails($newMails)
    {
        $this->new_mails = $newMails;
    }

    /**
     * Get new_mails
     *
     * @return boolean 
     */
    public function getNewMails()
    {
        return $this->new_mails;
    }

    /**
     * Set date_last_mail
     *
     * @param datetime $dateLastMail
     */
    public function setDateLastMail($dateLastMail)
    {
        $this->date_last_mail = $dateLastMail;
    }

    /**
     * Get date_last_mail
     *
     * @return datetime 
     */
    public function getDateLastMail()
    {
        return $this->date_last_mail;
    }
}
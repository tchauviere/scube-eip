<?php
// src/Scube/BaseBundle/Entity/Mail.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="mail")
 */
class Mail
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
     * @ORM\Column(type="datetime")
     */
    protected $mailing_date;

    /**
     * @ORM\ManyToOne(targetEntity="Scube\BaseBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $author;
	
	/**
     * @ORM\Column(type="string", length=5)
     */
    protected $type;
	
	/**
     * @ORM\Column(type="text")
     */
    protected $message;


    /**
     * Set mailing_date
     *
     * @param datetime $mailingDate
     */
    public function setMailingDate($mailingDate)
    {
        $this->mailing_date = $mailingDate;
    }

    /**
     * Get mailing_date
     *
     * @return datetime 
     */
    public function getMailingDate()
    {
        return $this->mailing_date;
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

    /**
     * Set message
     *
     * @param text $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return text 
     */
    public function getMessage()
    {
        return $this->message;
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
     * Set author
     *
     * @param Scube\BaseBundle\Entity\User $author
     */
    public function setAuthor(\Scube\BaseBundle\Entity\User $author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return Scube\BaseBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
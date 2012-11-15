<?php

namespace Scube\GoldBookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\MaxLength;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
{
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
    }
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $userId;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $inform;
	
	/**
     * @ORM\Column(type="text", nullable="true")
     */
    protected $comm;
	
	/**
     * @ORM\Column(type="text", nullable="true")
     */
    protected $idea;
    
    //put your code here.
    
    public function __construct()
    {        
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
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * Set userId
     *
     * @param text $inform
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    
    /**
     * Get inform
     *
     * @return boolean 
     */
    public function getInform()
    {
        return $this->inform;
    }
    
    /**
     * Set inform
     *
     * @param boolean $inform
     */
    public function setInform($inform)
    {
        $this->inform = $inform;
    }
    
    /**
     * Get comm
     *
     * @return text 
     */
    public function getComm()
    {
        return $this->comm;
    }
    
    /**
     * Set comm
     *
     * @param string $comm
     */
    public function setComm($comm)
    {
        $this->comm = $comm;
    }
    
    /**
     * Get idea
     *
     * @return text 
     */
    public function getIdea()
    {
        return $this->idea;
    }
    
    /**
     * Set idea
     *
     * @param string $idea
     */
    public function setIdea($idea)
    {
        $this->idea = $idea;
    }
}
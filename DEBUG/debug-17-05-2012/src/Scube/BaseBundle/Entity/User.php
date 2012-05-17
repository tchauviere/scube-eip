<?php
// src/Scube/BaseBundle/Entity/User.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
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
    protected $firstname;
	
	/**
     * @ORM\Column(type="string", length=100)
     */
    protected $surname;
	
	/**
     * @ORM\Column(type="string", unique="true", length=150)
     */
    protected $email;
	
	/**
     * @ORM\Column(type="string", length=50)
     */
    protected $password;

    /**
     * @ORM\Column(type="date")
     */
    protected $birthday;
	
	/**
     * @ORM\Column(type="string", length=50)
     */
    protected $gender;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\BaseInterface")
     * @ORM\JoinColumn(name="base_interface", referencedColumnName="id")
     */
    protected $baseInterface;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\PermissionsGroup")
     * @ORM\JoinColumn(name="permissions_group", referencedColumnName="id")
     */
    protected $permissionsGroup;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\UserProfile")
     * @ORM\JoinColumn(name="user_profile", referencedColumnName="id")
     */
    protected $profile;
	
    public function __construct()
    {
        $this->applications = new \Doctrine\Common\Collections\ArrayCollection();
    $this->admin_applications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set surname
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set birthday
     *
     * @param date $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * Get birthday
     *
     * @return date 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set gender
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set baseInterface
     *
     * @param Scube\BaseBundle\Entity\BaseInterface $baseInterface
     */
    public function setBaseInterface(\Scube\BaseBundle\Entity\BaseInterface $baseInterface)
    {
        $this->baseInterface = $baseInterface;
    }

    /**
     * Get baseInterface
     *
     * @return Scube\BaseBundle\Entity\BaseInterface 
     */
    public function getBaseInterface()
    {
        return $this->baseInterface;
    }

    /**
     * Set profile
     *
     * @param Scube\BaseBundle\Entity\UserProfile $profile
     */
    public function setProfile(\Scube\BaseBundle\Entity\UserProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get profile
     *
     * @return Scube\BaseBundle\Entity\UserProfile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set permissionsGroup
     *
     * @param Scube\BaseBundle\Entity\PermissionsGroup $permissionsGroup
     */
    public function setPermissionsGroup(\Scube\BaseBundle\Entity\PermissionsGroup $permissionsGroup)
    {
        $this->permissionsGroup = $permissionsGroup;
    }

    /**
     * Get permissionsGroup
     *
     * @return Scube\BaseBundle\Entity\PermissionsGroup 
     */
    public function getPermissionsGroup()
    {
        return $this->permissionsGroup;
    }
}
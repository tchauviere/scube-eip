<?php
// src/Scube/BaseBundle/Entity/Users.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class Users
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
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\Apps")
	 * @ORM\JoinTable(name="users_apps") 
     */ 
    protected $apps;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\Apps")
	 * @ORM\JoinTable(name="users_adminapps") 
     */ 
    protected $adminapps;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\BaseInterface")
     * @ORM\JoinColumn(name="base_interface", referencedColumnName="id")
     */
    protected $baseInterface;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\UsersProfile")
     * @ORM\JoinColumn(name="users_profile", referencedColumnName="id")
     */
    protected $profile;
	
	public function __construct()
    {
        $this->apps = new ArrayCollection();
		$this->adminapps = new ArrayCollection();
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
     * Add apps
     *
     * @param Scube\BaseBundle\Entity\Apps $apps
     */
    public function addApps(\Scube\BaseBundle\Entity\Apps $apps)
    {
        $this->apps[] = $apps;
    }

    /**
     * Get apps
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getApps()
    {
        return $this->apps;
    }

    /**
     * Get adminapps
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAdminapps()
    {
        return $this->adminapps;
    }

    /**
     * Set inteface
     *
     * @param Scube\BaseBundle\Entity\BaseInterface $interface
     */
    public function setBaseInterface(\Scube\BaseBundle\Entity\BaseInterface $inteface)
    {
        $this->baseInterface = $interface;
    }

    /**
     * Get inteface
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
     * @param Scube\BaseBundle\Entity\UsersProfile $profile
     */
    public function setProfile(\Scube\BaseBundle\Entity\UsersProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get profile
     *
     * @return Scube\BaseBundle\Entity\UsersProfile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
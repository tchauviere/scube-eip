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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $fb_id;
	
	/**
     * @ORM\Column(type="boolean")
     */
    protected $online;
	
	/**
     * @ORM\Column(type="boolean")
     */
    protected $blocked;
	
	/**
     * @ORM\Column(type="datetime")
     */
    protected $date_register;
	
	/**
     * @ORM\Column(type="datetime")
     */
    protected $date_last_access;
	
	/**
     * @ORM\Column(type="string", length=5)
     */
    protected $locale;
	
	/**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $ip;
	
	/**
     * @ORM\Column(type="boolean")
     */
    protected $maintenance_permission;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\BaseInterface")
     * @ORM\JoinColumn(name="base_interface", referencedColumnName="id")
     */
    protected $baseInterface;
	
	/**
     * @ORM\ManyToOne(targetEntity="Scube\BaseBundle\Entity\PermissionsGroup")
     * @ORM\JoinColumn(name="permissions_group", referencedColumnName="id")
     */
    protected $permissionsGroup;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\ConnectionsGroup")
	 * @ORM\JoinTable(name="connections_groups")
     */ 
    protected $connectionsGroups;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\News")
	 * @ORM\JoinTable(name="newsfeed")
     */ 
    protected $newsfeed;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\UserProfile")
     * @ORM\JoinColumn(name="user_profile", referencedColumnName="id")
     */
    protected $profile;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\Calendar")
     * @ORM\JoinColumn(name="calendar", referencedColumnName="id")
     */
    protected $calendar;
	
	/**
     * @ORM\OneToOne(targetEntity="Scube\BaseBundle\Entity\Mailbox")
     * @ORM\JoinColumn(name="mailbox", referencedColumnName="id")
     */
    protected $mailbox;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\MediaFolder")
	 * @ORM\JoinTable(name="media_folders")
     */ 
    protected $media_folders;
	
	/**
     * @ORM\ManyToMany(targetEntity="Scube\BaseBundle\Entity\TorrentFolder")
	 * @ORM\JoinTable(name="torrent_folders")
     */ 
    protected $torrent_folders;
	
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

    /**
     * Add connectionsGroups
     *
     * @param Scube\BaseBundle\Entity\ConnectionsGroup $connectionsGroups
     */
    public function addConnectionsGroup(\Scube\BaseBundle\Entity\ConnectionsGroup $connectionsGroups)
    {
        $this->connectionsGroups[] = $connectionsGroups;
    }

    /**
     * Get connectionsGroups
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getConnectionsGroups()
    {
        return $this->connectionsGroups;
    }

    /**
     * Add newsfeed
     *
     * @param Scube\BaseBundle\Entity\News $newsfeed
     */
    public function addNews(\Scube\BaseBundle\Entity\News $newsfeed)
    {
        $this->newsfeed[] = $newsfeed;
    }

    /**
     * Get newsfeed
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getNewsfeed()
    {
        return $this->newsfeed;
    }

    /**
     * Set calendar
     *
     * @param Scube\BaseBundle\Entity\Calendar $calendar
     */
    public function setCalendar(\Scube\BaseBundle\Entity\Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * Get calendar
     *
     * @return Scube\BaseBundle\Entity\Calendar 
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * Set mailbox
     *
     * @param Scube\BaseBundle\Entity\Mailbox $mailbox
     */
    public function setMailbox(\Scube\BaseBundle\Entity\Mailbox $mailbox)
    {
        $this->mailbox = $mailbox;
    }

    /**
     * Get mailbox
     *
     * @return Scube\BaseBundle\Entity\Mailbox 
     */
    public function getMailbox()
    {
        return $this->mailbox;
    }

    /**
     * Set online
     *
     * @param boolean $online
     */
    public function setOnline($online)
    {
        $this->online = $online;
    }

    /**
     * Get online
     *
     * @return boolean 
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Add torrent_folders
     *
     * @param Scube\BaseBundle\Entity\TorrentFolder $torrentFolders
     */
    public function addTorrentFolder(\Scube\BaseBundle\Entity\TorrentFolder $torrentFolders)
    {
        $this->torrent_folders[] = $torrentFolders;
    }

    /**
     * Get torrent_folders
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTorrentFolders()
    {
        return $this->torrent_folders;
    }

    /**
     * Add media_folders
     *
     * @param Scube\BaseBundle\Entity\MediaFolder $mediaFolders
     */
    public function addMediaFolder(\Scube\BaseBundle\Entity\MediaFolder $mediaFolders)
    {
        $this->media_folders[] = $mediaFolders;
    }

    /**
     * Get media_folders
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMediaFolders()
    {
        return $this->media_folders;
    }

    /**
     * Set blocked
     *
     * @param boolean $blocked
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
    }

    /**
     * Get blocked
     *
     * @return boolean 
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Set date_register
     *
     * @param date $dateRegister
     */
    public function setDateRegister($dateRegister)
    {
        $this->date_register = $dateRegister;
    }

    /**
     * Get date_register
     *
     * @return date 
     */
    public function getDateRegister()
    {
        return $this->date_register;
    }

    /**
     * Set date_last_access
     *
     * @param datetime $dateLastAccess
     */
    public function setDateLastAccess($dateLastAccess)
    {
        $this->date_last_access = $dateLastAccess;
    }

    /**
     * Get date_last_access
     *
     * @return datetime 
     */
    public function getDateLastAccess()
    {
        return $this->date_last_access;
    }

    /**
     * Set locale
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set ip
     *
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set maintenance_permission
     *
     * @param boolean $maintenancePermission
     */
    public function setMaintenancePermission($maintenancePermission)
    {
        $this->maintenance_permission = $maintenancePermission;
    }

    /**
     * Get maintenance_permission
     *
     * @return boolean 
     */
    public function getMaintenancePermission()
    {
        return $this->maintenance_permission;
    }
	
	 /**
     * Set fb_id
     *
     * @param string $fbId
     */
    public function setFbId($fbId)
    {
        $this->fb_id = $fbId;
    }

    /**
     * Get fb_id
     *
     * @return string 
     */
    public function getFbId()
    {
        return $this->fb_id;
    }
}
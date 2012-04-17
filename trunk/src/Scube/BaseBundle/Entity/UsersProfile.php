<?php
// src/Scube/BaseBundle/Entity/UsersProfile.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users_profile")
 */
class UsersProfile
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
    protected $picture;
	
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $status;

    /**
     * @ORM\Column(type="string", length=5)
     */
    protected $language;

    /**
     * @ORM\Column(type="integer")
     */
    protected $phone_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $phone_number;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $native_city;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $address;

    /**
     * @ORM\Column(type="integer")
     */
    protected $postal_code;

    /**
     * @ORM\Column(type="string", length=5)
     */
    protected $tongues;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $website;

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
     * Set picture
     *
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set language
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set phone_id
     *
     * @param integer $phoneId
     */
    public function setPhoneId($phoneId)
    {
        $this->phone_id = $phoneId;
    }

    /**
     * Get phone_id
     *
     * @return integer 
     */
    public function getPhoneId()
    {
        return $this->phone_id;
    }

    /**
     * Set phone_number
     *
     * @param integer $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phone_number = $phoneNumber;
    }

    /**
     * Get phone_number
     *
     * @return integer 
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Set native_city
     *
     * @param string $nativeCity
     */
    public function setNativeCity($nativeCity)
    {
        $this->native_city = $nativeCity;
    }

    /**
     * Get native_city
     *
     * @return string 
     */
    public function getNativeCity()
    {
        return $this->native_city;
    }

    /**
     * Set city
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postal_code
     *
     * @param integer $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postal_code = $postalCode;
    }

    /**
     * Get postal_code
     *
     * @return integer 
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Set tongues
     *
     * @param string $tongues
     */
    public function setTongues($tongues)
    {
        $this->tongues = $tongues;
    }

    /**
     * Get tongues
     *
     * @return string 
     */
    public function getTongues()
    {
        return $this->tongues;
    }

    /**
     * Set website
     *
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
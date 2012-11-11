<?php

namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scube\BaseBundle\Entity\Social_Networks
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Social_Networks
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $api_key
     *
     * @ORM\Column(name="api_key", type="string", length=255)
     */
    private $api_key;

    /**
     * @var string $api_secret
     *
     * @ORM\Column(name="api_secret", type="string", length=255)
     */
    private $api_secret;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set api_key
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->api_key = $apiKey;
    }

    /**
     * Get api_key
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Set api_secret
     *
     * @param string $apiSecret
     */
    public function setApiSecret($apiSecret)
    {
        $this->api_secret = $apiSecret;
    }

    /**
     * Get api_secret
     *
     * @return string 
     */
    public function getApiSecret()
    {
        return $this->api_secret;
    }
}
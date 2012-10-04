<?php
// src/Scube/BaseBundle/Entity/News.php
namespace Scube\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="news")
 */
class News
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
    protected $author;
	
	/**
     * @ORM\Column(type="datetime")
     */
    protected $post_date;
	
	/**
     * @ORM\Column(type="text")
     */
    protected $content_text;
	

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
     * Set post_date
     *
     * @param datetime $postDate
     */
    public function setPostDate($postDate)
    {
        $this->post_date = $postDate;
    }

    /**
     * Get post_date
     *
     * @return datetime 
     */
    public function getPostDate()
    {
        return $this->post_date;
    }

    /**
     * Set content_text
     *
     * @param text $contentText
     */
    public function setContentText($contentText)
    {
        $this->content_text = $contentText;
    }

    /**
     * Get content_text
     *
     * @return text 
     */
    public function getContentText()
    {
        return $this->content_text;
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
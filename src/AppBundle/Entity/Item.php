<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @see http://www.rssboard.org/rss-profile#element-channel-item
 *
 * @ApiResource(attributes={
 *   "filters"={"item.search_filter", "item.range_filter"}
 * })
 * @ORM\Entity
 */
class Item
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $author;

    /**
     * @var ArrayCollection(Category)
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $guid;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $guidIsPermaLink = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pubDate;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $enclosureLength;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $enclosureType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $enclosureUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $sourceUrl;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Channel
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Channel
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Channel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Channel
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Channel
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set guid
     *
     * @param string $guid
     *
     * @return Channel
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set guidIsPermaLink
     *
     * @param boolean $guidIsPermaLink
     *
     * @return Channel
     */
    public function setGuidIsPermaLink($guidIsPermaLink)
    {
        $this->guidIsPermaLink = $guidIsPermaLink;

        return $this;
    }

    /**
     * Get guidIsPermaLink
     *
     * @return boolean
     */
    public function getGuidIsPermaLink()
    {
        return $this->guidIsPermaLink;
    }

    /**
     * Set pubDate
     *
     * @param \DateTime $pubDate
     *
     * @return Channel
     */
    public function setPubDate($pubDate)
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    /**
     * Get pubDate
     *
     * @return \DateTime
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * Set enclosureLength
     *
     * @param integer $enclosureLength
     *
     * @return Channel
     */
    public function setEnclosureLength($enclosureLength)
    {
        $this->enclosureLength = $enclosureLength;

        return $this;
    }

    /**
     * Get enclosureLength
     *
     * @return integer
     */
    public function getEnclosureLength()
    {
        return $this->enclosureLength;
    }

    /**
     * Set enclosureType
     *
     * @param string $enclosureType
     *
     * @return Channel
     */
    public function setEnclosureType($enclosureType)
    {
        $this->enclosureType = $enclosureType;

        return $this;
    }

    /**
     * Get enclosureType
     *
     * @return string
     */
    public function getEnclosureType()
    {
        return $this->enclosureType;
    }

    /**
     * Set enclosureUrl
     *
     * @param string $enclosureUrl
     *
     * @return Channel
     */
    public function setEnclosureUrl($enclosureUrl)
    {
        $this->enclosureUrl = $enclosureUrl;

        return $this;
    }

    /**
     * Get enclosureUrl
     *
     * @return string
     */
    public function getEnclosureUrl()
    {
        return $this->enclosureUrl;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Channel
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set sourceUrl
     *
     * @param string $sourceUrl
     *
     * @return Channel
     */
    public function setSourceUrl($sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;

        return $this;
    }

    /**
     * Get sourceUrl
     *
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    /**
     * Set duration
     *
     * @param string $duration
     *
     * @return Channel
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }
}

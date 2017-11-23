<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use AppBundle\Traits\CategorizableTrait;
use AppBundle\Traits\RssItemTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @see http://www.rssboard.org/rss-profile#element-channel-item
 *
 * @ApiResource(
 *   collectionOperations={"get"={"method"="GET"}},
 *   itemOperations={"get"={"method"="GET"}},
 *   attributes={
 *     "filters"={
 *       "item.search_filter",
 *       "item.range_filter",
 *       "item.published_filter"
 *     }
 *   })
 * @ORM\Entity
 */
class Item implements CategorizableInterface, Timestampable, SoftDeleteable
{
    use SoftDeleteableEntity;
    use CategorizableTrait;
    use TimestampableEntity;
    use RssItemTrait;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var Feed
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Feed")
     */
    private $feed;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Subject", inversedBy="items")
     */
    private $subjects;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    public function __toString()
    {
        return $this->title.' ['.$this->link.']';
    }

    /**
     * Get id.
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set duration.
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
     * Get duration.
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set feed.
     *
     * @param \AppBundle\Entity\Feed $feed
     *
     * @return Item
     */
    public function setFeed(\AppBundle\Entity\Feed $feed = null)
    {
        $this->feed = $feed;

        return $this;
    }

    /**
     * Get feed.
     *
     * @return \AppBundle\Entity\Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * Set publishedAt.
     *
     * @param \DateTime $publishedAt
     *
     * @return Item
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt.
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set categoryList
     *
     * @param tag_list $categoryList
     *
     * @return Item
     */
    public function setCategoryList($categoryList)
    {
        $this->categoryList = $categoryList;

        return $this;
    }

    /**
     * Get categoryList
     *
     * @return tag_list
     */
    public function getCategoryList()
    {
        return $this->categoryList;
    }

    /**
     * Set tagList
     *
     * @param tag_list $tagList
     *
     * @return Item
     */
    public function setTagList($tagList)
    {
        $this->tagList = $tagList;

        return $this;
    }

    /**
     * Get tagList
     *
     * @return tag_list
     */
    public function getTagList()
    {
        return $this->tagList;
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
     * Get explicit
     *
     * @return boolean
     */
    public function getExplicit()
    {
        return $this->explicit;
    }

    /**
     * Add subject
     *
     * @param \AppBundle\Entity\Subject $subject
     *
     * @return Item
     */
    public function addSubject(Subject $subject)
    {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject
     *
     * @param \AppBundle\Entity\Subject $subject
     */
    public function removeSubject(Subject $subject)
    {
        $this->subjects->removeElement($subject);
    }

    /**
     * Get subjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }
}

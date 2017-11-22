<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use AppBundle\Traits\CategorizableTrait;
use AppBundle\Traits\RssItemTrait;
use AppBundle\Traits\TaggableTrait;
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
    use TaggableTrait;
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="items")
     * @ORM\JoinTable(name="items_tags")
     */
    private $tags;

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
}

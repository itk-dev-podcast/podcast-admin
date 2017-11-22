<?php

namespace AppBundle\Entity;

use AppBundle\Traits\RssChannelTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @see http://www.rssboard.org/rss-profile#element-channel
 *
 * @ORM\Entity
 */
class Channel
{
    use RssChannelTrait;

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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Feed")
     */
    private $feed;

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
     * @return Feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param Feed $feed
     *
     * @return Channel
     */
    public function setFeed(Feed $feed)
    {
        $this->feed = $feed;

        return $this;
    }
}

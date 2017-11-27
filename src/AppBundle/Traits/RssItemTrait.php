<?php

namespace AppBundle\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @see https://cyber.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
 *
 * Trait RssItem
 */
trait RssItemTrait
{
    // ------------------------------------------------------------------------
    // Core RSS item properties
    // @see https://cyber.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
    // ------------------------------------------------------------------------

    /**
     * @var string
     *
     * @Groups("read")
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @Groups("read")
     *
     * @ORM\Column(type="string")
     */
    private $link;

    /**
     * @var string
     *
     * @Groups("read")
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @Groups("read")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $author;

    /**
     * @Groups("read")
     *
     * @var ArrayCollection(Category)
     */
    private $categories;

    /**
     * @var string
     *
     * @Groups("read")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @var array
     *
     * @Groups("read")
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $enclosure;

    /**
     * @var string
     *
     * @Groups("read")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $guid;

    /**
     * @var bool
     *
     * @Groups("read")
     *
     * @ORM\Column(type="boolean")
     */
    private $guidIsPermaLink = false;

    /**
     * @var \DateTime
     *
     * @Groups("read")
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pubDate;

    /**
     * @var array
     *
     * @Groups("read")
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $source;

    // ------------------------------------------------------------------------
    // iTunes RSS item properties
    // @see https://help.apple.com/itc/podcasts_connect/#/itcb54353390
    // ------------------------------------------------------------------------

    /**
     * @var string
     *
     * @Groups("read")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @Groups("read")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @var int
     *
     * @Groups("read")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var int
     *
     * @Groups("read")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $episode;

    /**
     * @var string
     *
     * @Groups("read")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $episodeType;

    /**
     * @var bool
     *
     * @Groups("read")
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $explicit;

    /**
     * @var array
     *
     * @Groups("read")
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $image;

    /**
     * @var int
     *
     * @Groups("read")
     *
     * @ORM\Column(type="integer", nullable=true, name="`order`")
     */
    private $order;

    /**
     * @var int
     *
     * @Groups("read")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $season;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return RssItemTrait
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     *
     * @return RssItemTrait
     */
    public function setLink(string $link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return RssItemTrait
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     *
     * @return RssItemTrait
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     *
     * @return RssItemTrait
     */
    public function setComments(string $comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return array
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param array $enclosure
     *
     * @return RssItemTrait
     */
    public function setEnclosure(array $enclosure)
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param string $guid
     *
     * @return RssItemTrait
     */
    public function setGuid(string $guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGuidIsPermaLink()
    {
        return $this->guidIsPermaLink;
    }

    /**
     * @param bool $guidIsPermaLink
     *
     * @return RssItemTrait
     */
    public function setGuidIsPermaLink(bool $guidIsPermaLink)
    {
        $this->guidIsPermaLink = $guidIsPermaLink;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * @param \DateTime $pubDate
     *
     * @return RssItemTrait
     */
    public function setPubDate(\DateTime $pubDate)
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    /**
     * @return array
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param array $source
     *
     * @return RssItemTrait
     */
    public function setSource(array $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     *
     * @return RssItemTrait
     */
    public function setSubtitle(string $subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     *
     * @return RssItemTrait
     */
    public function setSummary(string $summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     *
     * @return RssItemTrait
     */
    public function setDuration(int $duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return int
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * @param int $episode
     *
     * @return RssItemTrait
     */
    public function setEpisode(int $episode)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * @return string
     */
    public function getEpisodeType()
    {
        return $this->episodeType;
    }

    /**
     * @param string $episodeType
     *
     * @return RssItemTrait
     */
    public function setEpisodeType(string $episodeType)
    {
        $this->episodeType = $episodeType;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExplicit()
    {
        return $this->explicit;
    }

    /**
     * @param bool $explicit
     *
     * @return RssItemTrait
     */
    public function setExplicit(bool $explicit)
    {
        $this->explicit = $explicit;

        return $this;
    }

    /**
     * @return array
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param array $image
     *
     * @return RssItemTrait
     */
    public function setImage(array $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     *
     * @return RssItemTrait
     */
    public function setOrder(int $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return int
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param int $season
     *
     * @return RssItemTrait
     */
    public function setSeason(int $season)
    {
        $this->season = $season;

        return $this;
    }
}

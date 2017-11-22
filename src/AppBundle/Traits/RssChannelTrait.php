<?php

namespace AppBundle\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait RssChannelTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
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
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $copyright;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $managingEditor;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $webMaster;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pubDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastBuildDate;

    /**
     * @var ArrayCollection(Category)
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $generator;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $docs;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $cloud;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ttl;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $rating;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $textInput;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $skipHours;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $skipDays;

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
     * @return RssChannelTrait
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
     * @return RssChannelTrait
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
     * @return RssChannelTrait
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return RssChannelTrait
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * @param string $copyright
     *
     * @return RssChannelTrait
     */
    public function setCopyright(string $copyright)
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * @return string
     */
    public function getManagingEditor()
    {
        return $this->managingEditor;
    }

    /**
     * @param string $managingEditor
     *
     * @return RssChannelTrait
     */
    public function setManagingEditor(string $managingEditor)
    {
        $this->managingEditor = $managingEditor;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebMaster()
    {
        return $this->webMaster;
    }

    /**
     * @param string $webMaster
     *
     * @return RssChannelTrait
     */
    public function setWebMaster(string $webMaster)
    {
        $this->webMaster = $webMaster;

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
     * @return RssChannelTrait
     */
    public function setPubDate(\DateTime $pubDate)
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastBuildDate()
    {
        return $this->lastBuildDate;
    }

    /**
     * @param \DateTime $lastBuildDate
     *
     * @return RssChannelTrait
     */
    public function setLastBuildDate(\DateTime $lastBuildDate)
    {
        $this->lastBuildDate = $lastBuildDate;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     *
     * @return RssChannelTrait
     */
    public function setCategories(ArrayCollection $categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return string
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * @param string $generator
     *
     * @return RssChannelTrait
     */
    public function setGenerator(string $generator)
    {
        $this->generator = $generator;

        return $this;
    }

    /**
     * @return string
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     * @param string $docs
     *
     * @return RssChannelTrait
     */
    public function setDocs(string $docs)
    {
        $this->docs = $docs;

        return $this;
    }

    /**
     * @return string
     */
    public function getCloud()
    {
        return $this->cloud;
    }

    /**
     * @param string $cloud
     *
     * @return RssChannelTrait
     */
    public function setCloud(string $cloud)
    {
        $this->cloud = $cloud;

        return $this;
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     *
     * @return RssChannelTrait
     */
    public function setTtl(int $ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return RssChannelTrait
     */
    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param string $rating
     *
     * @return RssChannelTrait
     */
    public function setRating(string $rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return array
     */
    public function getTextInput()
    {
        return $this->textInput;
    }

    /**
     * @param array $textInput
     *
     * @return RssChannelTrait
     */
    public function setTextInput(array $textInput)
    {
        $this->textInput = $textInput;

        return $this;
    }

    /**
     * @return array
     */
    public function getSkipHours()
    {
        return $this->skipHours;
    }

    /**
     * @param array $skipHours
     *
     * @return RssChannelTrait
     */
    public function setSkipHours(array $skipHours)
    {
        $this->skipHours = $skipHours;

        return $this;
    }

    /**
     * @return array
     */
    public function getSkipDays()
    {
        return $this->skipDays;
    }

    /**
     * @param array $skipDays
     *
     * @return RssChannelTrait
     */
    public function setSkipDays(array $skipDays)
    {
        $this->skipDays = $skipDays;

        return $this;
    }
}

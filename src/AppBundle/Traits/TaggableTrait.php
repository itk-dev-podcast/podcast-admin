<?php

namespace AppBundle\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FPN\TagBundle\Entity\Tag;

trait TaggableTrait
{
    /**
     * @var ArrayCollection
     *
     * @ORM\Column(type="tag_list", nullable=true)
     */
    private $tagList;

    private $tags;

    public function setTags(ArrayCollection $tags)
    {
        $this->tags = $tags;
        // This ensures that the entity is dirty when doctrine persists it.
        $this->tagList = $tags->map(function (Tag $tag) {
            return $tag->getName();
        });

        return $this;
    }

    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    public function getTaggableType()
    {
        return __CLASS__;
    }

    public function getTaggableId()
    {
        return $this->getId();
    }
}

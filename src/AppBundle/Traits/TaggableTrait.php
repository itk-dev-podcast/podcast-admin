<?php

namespace AppBundle\Traits;

use Doctrine\Common\Collections\ArrayCollection;

trait TaggableTrait
{
    private $tags;

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

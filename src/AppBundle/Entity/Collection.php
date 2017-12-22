<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * A dynamic collection of items (based on a search query).
 *
 * @ApiResource(
 *   collectionOperations={"get"={"method"="GET"}},
 *   itemOperations={"get"={"method"="GET"}},
 *   attributes={
 *     "normalization_context"={"groups"={"read"}},
 *     "filters"={
 *       "collection.search_filter"
 *     }
 *   })
 * @ORM\Entity
 */
class Collection implements Timestampable, SoftDeleteable
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

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
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="json_array", nullable=false)
     */
    private $query;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    public function __toString()
    {
        return $this->title;
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
     * Set title.
     *
     * @param string $title
     *
     * @return Collection
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Collection
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set query.
     *
     * @param array $query
     *
     * @return Collection
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query.
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set publishedAt.
     *
     * @param \DateTime $publishedAt
     *
     * @return Collection
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

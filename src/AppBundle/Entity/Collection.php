<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * A dynamic collection of items (based on a search query).
 *
 * @ApiResource(
 *   collectionOperations={"get"={"method"="GET"}},
 *   itemOperations={"get"={"method"="GET"}},
 *   attributes={
 *     "normalization_context"={"groups"={"read_collection"}},
 *     "filters"={
 *       "collection.search_filter",
 *       "collection.published_filter"
 *     }
 *   })
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Collection implements Timestampable, SoftDeleteable
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @Groups("read_collection")
     *
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups("read_collection")
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Groups("read_collection")
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * A query for dynamic collections.
     *
     * @var string
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $itemQuery;

    /**
     * @var ArrayCollection
     *
     * @Groups("read_collection")
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Item")
     */
    private $items;

    /**
     * @var \DateTime
     *
     * @Groups("read_collection")
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups("read_collection")
     *
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="collection_image", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

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
     * @param array $itemQuery
     *
     * @return Collection
     */
    public function setItemQuery($itemQuery)
    {
        $this->itemQuery = $itemQuery;

        return $this;
    }

    /**
     * Get query.
     *
     * @return array
     */
    public function getItemQuery()
    {
        return $this->itemQuery;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems($items)
    {
        $this->items = $items;
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

    public function setImageFile($image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
}

<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use AppBundle\Entity\Taxonomy\Audience;
use AppBundle\Entity\Taxonomy\Context;
use AppBundle\Entity\Taxonomy\Recommender;
use AppBundle\Entity\Taxonomy\Subject;
use AppBundle\Traits\RssItemTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @see http://www.rssboard.org/rss-profile#element-channel-item
 *
 * @ApiResource(
 *   collectionOperations={"get"={"method"="GET"}},
 *   itemOperations={"get"={"method"="GET"}},
 *   attributes={
 *     "normalization_context"={"groups"={"read"}},
 *     "filters"={
 *       "item.search_filter",
 *       "item.taxonomy_filter",
 *       "item.range_filter",
 *       "item.published_filter",
 *       "item.geolocation_filter"
 *     }
 *   })
 * @ORM\Entity
 */
class Item implements Timestampable, SoftDeleteable
{
    use SoftDeleteableEntity;
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
     * @Groups("read")
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Taxonomy\Category", inversedBy="items")
     */
    private $categories;

    /**
     * @var ArrayCollection
     *
     * @Groups("read")
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Taxonomy\Subject", inversedBy="items")
     */
    private $subjects;

    /**
     * @var ArrayCollection
     *
     * @Groups("read")
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Taxonomy\Recommender", inversedBy="items")
     */
    private $recommenders;

    /**
     * @var ArrayCollection
     *
     * @Groups("read")
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Taxonomy\Context", inversedBy="items")
     */
    private $contexts;

    /**
     * @var ArrayCollection
     *
     * @Groups("read")
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Taxonomy\Audience", inversedBy="items")
     */
    private $audiences;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @var float
     *
     * @Groups("read")
     *
     * @ORM\Column(type="decimal", precision=8, scale=5, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @Groups("read")
     *
     * @ORM\Column(type="decimal", precision=8, scale=5, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $query;

    /**
     * @var ArrayCollection
     *
     * @Groups("read")
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Taxonomy\RelatedMaterial", inversedBy="items")
     */
    private $relatedMaterials;

    /**
     * @var ArrayCollection
     *
     * @Groups("read")
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Taxonomy\RelatedEvent", inversedBy="items")
     */
    private $relatedEvents;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->recommenders = new ArrayCollection();
        $this->contexts = new ArrayCollection();
        $this->audiences = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title.' ['.$this->link.']';
    }

    /**
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context)
    {
        $relatedMaterialIds = [];
        foreach ($this->getRelatedMaterials() as $index => $relatedMaterial) {
            if (isset($relatedMaterialIds[$relatedMaterial->getId()])) {
                $context->buildViolation('Related materials must be unique')
                    ->atPath('relatedMaterials['.$index.']')
                    ->addViolation();
            }
            $relatedMaterialIds[$relatedMaterial->getId()] = true;
        }
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
     * Get guidIsPermaLink.
     *
     * @return bool
     */
    public function getGuidIsPermaLink()
    {
        return $this->guidIsPermaLink;
    }

    /**
     * Get explicit.
     *
     * @return bool
     */
    public function getExplicit()
    {
        return $this->explicit;
    }

    /**
     * Add subject.
     *
     * @param \AppBundle\Entity\Taxonomy\Subject $subject
     *
     * @return Item
     */
    public function addSubject(Subject $subject)
    {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject.
     *
     * @param \AppBundle\Entity\Taxonomy\Subject $subject
     */
    public function removeSubject(Subject $subject)
    {
        $this->subjects->removeElement($subject);
    }

    /**
     * Get subjects.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Add recommender.
     *
     * @param \AppBundle\Entity\Taxonomy\Recommender $recommender
     *
     * @return Item
     */
    public function addRecommender(Recommender $recommender)
    {
        $this->recommenders[] = $recommender;

        return $this;
    }

    /**
     * Remove recommender.
     *
     * @param \AppBundle\Entity\Taxonomy\Recommender $recommender
     */
    public function removeRecommender(Recommender $recommender)
    {
        $this->recommenders->removeElement($recommender);
    }

    /**
     * Get recommenders.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecommenders()
    {
        return $this->recommenders;
    }

    /**
     * Add context.
     *
     * @param \AppBundle\Entity\Taxonomy\Context $context
     *
     * @return Item
     */
    public function addContext(Context $context)
    {
        $this->contexts[] = $context;

        return $this;
    }

    /**
     * Remove context.
     *
     * @param \AppBundle\Entity\Taxonomy\Context $context
     */
    public function removeContext(Context $context)
    {
        $this->contexts->removeElement($context);
    }

    /**
     * Get contexts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * Add audience.
     *
     * @param \AppBundle\Entity\Taxonomy\Audience $audience
     *
     * @return Item
     */
    public function addAudience(Audience $audience)
    {
        $this->audiences[] = $audience;

        return $this;
    }

    /**
     * Remove audience.
     *
     * @param \AppBundle\Entity\Taxonomy\Audience $audience
     */
    public function removeAudience(Audience $audience)
    {
        $this->audiences->removeElement($audience);
    }

    /**
     * Get audiences.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAudiences()
    {
        return $this->audiences;
    }

    /**
     * Set latitude.
     *
     * @param string $latitude
     *
     * @return Item
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude.
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude.
     *
     * @param string $longitude
     *
     * @return Item
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set query.
     *
     * @param string $query
     *
     * @return Item
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set relatedMaterials.
     *
     * @param ArrayCollection $relatedMaterials
     *
     * @return Item
     */
    public function setRelatedMaterials(ArrayCollection $relatedMaterials)
    {
        $this->relatedMaterials = $relatedMaterials;

        return $this;
    }

    /**
     * Get related materials.
     *
     * @return ArrayCollection
     */
    public function getRelatedMaterials()
    {
        return $this->relatedMaterials;
    }

    /**
     * Set relatedEvents.
     *
     * @param ArrayCollection $relatedEvents
     *
     * @return Item
     */
    public function setRelatedEvents(ArrayCollection $relatedEvents)
    {
        $this->relatedEvents = $relatedEvents;

        return $this;
    }

    /**
     * Get related events.
     *
     * @return ArrayCollection
     */
    public function getRelatedEvents()
    {
        return $this->relatedEvents;
    }
}

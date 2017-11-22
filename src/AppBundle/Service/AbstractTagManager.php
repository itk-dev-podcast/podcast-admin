<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use DoctrineExtensions\Taggable\TagManager as BaseTagManager;
use FPN\TagBundle\Entity\Tag;

/**
 * A generalization of DoctrineExtensions\Taggable\TagManager with configurable properties.
 *
 * Class TagManager
 */
abstract class AbstractTagManager //extends BaseTagManager
{
    protected $em;
    protected $tagClass;
    protected $taggingClass;
    protected $getTagsMethod;
    protected $taggingName;
    protected $tagIdName = 'tag_id';
    protected $tagName = 'tag';
    protected $getTaggableTypeMethod;
    protected $getTaggableIdMethod;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Adds a tag on the given taggable resource.
     *
     * @param Tag      $tag      Tag object
     * @param Taggable $resource Taggable resource
     */
    public function addTag($tag, $resource)
    {
        $this->getTags($resource)->add($tag);
    }

    /**
     * Adds multiple tags on the given taggable resource.
     *
     * @param Tag[]    $tags     Array of Tag objects
     * @param Taggable $resource Taggable resource
     */
    public function addTags(array $tags, $resource)
    {
        foreach ($tags as $tag) {
            if ($tag instanceof Tag) {
                $this->addTag($tag, $resource);
            }
        }
    }

    /**
     * Removes an existant tag on the given taggable resource.
     *
     * @param Tag      $tag      Tag object
     * @param Taggable $resource Taggable resource
     *
     * @return bool
     */
    public function removeTag($tag, $resource)
    {
        return $this->getTags($resource)->removeElement($tag);
    }

    /**
     * Replaces all current tags on the given taggable resource.
     *
     * @param Tag[]    $tags     Array of Tag objects
     * @param Taggable $resource Taggable resource
     */
    public function replaceTags(array $tags, $resource)
    {
        $this->getTags($resource)->clear();
        $this->addTags($tags, $resource);
    }

    /**
     * Loads or creates a tag from tag name.
     *
     * @param array $name Tag name
     *
     * @return Tag
     */
    public function loadOrCreateTag($name)
    {
        $tags = $this->loadOrCreateTags([$name]);

        return $tags[0];
    }

    /**
     * Loads or creates multiples tags from a list of tag names.
     *
     * @param array $names Array of tag names
     *
     * @return Tag[]
     */
    public function loadOrCreateTags(array $names)
    {
        if (empty($names)) {
            return [];
        }

        $names = array_unique($names);

        $builder = $this->em->createQueryBuilder();

        $tags = $builder
            ->select('t')
            ->from($this->tagClass, 't')

            ->where($builder->expr()->in('t.name', $names))

            ->getQuery()
            ->getResult()
        ;

        $loadedNames = [];
        foreach ($tags as $tag) {
            $loadedNames[] = $tag->getName();
        }

        $missingNames = array_udiff($names, $loadedNames, 'strcasecmp');
        if (count($missingNames)) {
            foreach ($missingNames as $name) {
                $tag = $this->createTag($name);
                $this->em->persist($tag);

                $tags[] = $tag;
            }

            $this->em->flush();
        }

        return $tags;
    }

    /**
     * Saves tags for the given taggable resource.
     *
     * @param Taggable $resource Taggable resource
     */
    public function saveTagging($resource)
    {
        $oldTags = $this->getTagging($resource);
        $newTags = $this->getTags($resource);
        $tagsToAdd = $newTags;

        if ($oldTags !== null and is_array($oldTags) and !empty($oldTags)) {
            $tagsToRemove = [];

            foreach ($oldTags as $oldTag) {
                if ($newTags->exists(function ($index, $newTag) use ($oldTag) {
                    return $newTag->getName() === $oldTag->getName();
                })) {
                    $tagsToAdd->removeElement($oldTag);
                } else {
                    $tagsToRemove[] = $oldTag->getId();
                }
            }

            if (count($tagsToRemove)) {
                $builder = $this->em->createQueryBuilder();
                $builder
                    ->delete($this->taggingClass, 't')
                    ->where('t.'.$this->tagIdName)
                    ->where($builder->expr()->in('t.'.$this->tagName, $tagsToRemove))
                    ->andWhere('t.resourceType = :resourceType')
                    ->setParameter('resourceType', $this->getTaggableType($resource))
                    ->andWhere('t.resourceId = :resourceId')
                    ->setParameter('resourceId', $this->getTaggableId($resource))
                    ->getQuery()
                    ->getResult()
                ;
            }
        }

        foreach ($tagsToAdd as $tag) {
            $this->em->persist($tag);
            $tagging = $this->createTagging($tag, $resource);
            $this->em->persist($tagging);
        }

        if (count($tagsToAdd)) {
            $this->em->flush();
        }
    }

    /**
     * Loads all tags for the given taggable resource.
     *
     * @param Taggable $resource Taggable resource
     */
    public function loadTagging($resource)
    {
        $tags = $this->getTagging($resource);
        $this->replaceTags($tags, $resource);
    }

    /**
     * Deletes all tagging records for the given taggable resource.
     *
     * @param Taggable $resource Taggable resource
     */
    public function deleteTagging($resource)
    {
        $taggingList = $this->em->createQueryBuilder()
            ->select('t')
            ->from($this->taggingClass, 't')

            ->where('t . resourceType = :type')
            ->setParameter('type', $this->getTaggableType($resource))

            ->andWhere('t . resourceId = :id')
            ->setParameter('id', $this->getTaggableId($resource))

            ->getQuery()
            ->getResult();

        foreach ($taggingList as $tagging) {
            $this->em->remove($tagging);
        }
    }

    /**
     * Splits an string into an array of valid tag names.
     *
     * @param string $names     String of tag names
     * @param string $separator Tag name separator
     */
    public function splitTagNames($names, $separator = ',')
    {
        $tags = explode($separator, $names);
        $tags = array_map('trim', $tags);
        $tags = array_filter($tags, function ($value) { return !empty($value); });

        return array_values($tags);
    }

    /**
     * Returns an array of tag names for the given Taggable resource.
     *
     * @param Taggable $resource Taggable resource
     */
    public function getTagNames($resource)
    {
        $names = [];

        if (count($this->getTags($resource)) > 0) {
            foreach ($this->getTags($resource) as $tag) {
                $names[] = $tag->getName();
            }
        }

        return $names;
    }

    /**
     * Gets all tags for the given taggable resource.
     *
     * @param Taggable $resource Taggable resource
     */
    protected function getTagging($resource)
    {
        return $this->em
            ->createQueryBuilder()

            ->select('t')
            ->from($this->tagClass, 't')

            ->innerJoin('t.'.$this->taggingName, 't2', Expr\Join::WITH, 't2 . resourceId = :id AND t2 . resourceType = :type')
            ->setParameter('id', $this->getTaggableId($resource))
            ->setParameter('type', $this->getTaggableType($resource))

            // ->orderBy('t.name', 'ASC')

            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Creates a new Tag object.
     *
     * @param string $name Tag name
     *
     * @return Tag
     */
    protected function createTag($name)
    {
        return new $this->tagClass($name);
    }

    /**
     * Creates a new Tagging object.
     *
     * @param Tag      $tag      Tag object
     * @param Taggable $resource Taggable resource object
     *
     * @return Tagging
     */
    protected function createTagging($tag, $resource)
    {
        return new $this->taggingClass($tag, $resource);
    }

    protected function getTags($resource)
    {
        return $resource->{$this->getTagsMethod}();
    }

    protected function getTaggableType($resource)
    {
        return $resource->{$this->getTaggableTypeMethod}();
    }

    protected function getTaggableId($resource)
    {
        return $resource->{$this->getTaggableIdMethod}();
    }
}

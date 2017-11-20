<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use DoctrineExtensions\Taggable\Taggable;
use FPN\TagBundle\Entity\TagManager;

class TagListener
{
    /** @var TagManager */
    private $tagManager;

    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof Taggable) {
            $this->tagManager->saveTagging($object);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->postPersist($args);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof Taggable) {
            $this->tagManager->loadTagging($object);
        }
    }
}

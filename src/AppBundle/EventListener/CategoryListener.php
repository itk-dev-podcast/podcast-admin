<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\CategorizableInterface;
use AppBundle\Service\CategoryManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use FPN\TagBundle\Entity\TagManager;

class CategoryListener
{
    /** @var TagManager */
    private $categoryManager;

    public function __construct(CategoryManager $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof CategorizableInterface) {
            $this->categoryManager->saveTagging($object);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->postPersist($args);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof CategorizableInterface) {
            $this->categoryManager->loadTagging($object);
        }
    }
}

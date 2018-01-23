<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Collection;
use AppBundle\Entity\Item;
use AppBundle\Service\EntitySearchInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CollectionListener
{
    private $search;

    public function __construct(EntitySearchInterface $container)
    {
        $this->search = $container;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof Collection) {
            if ($object->getItemQuery()) {
                $items = $this->search->search(Item::class, $object->getItemQuery());
                $object->setItems($items);
            }
        }
    }
}

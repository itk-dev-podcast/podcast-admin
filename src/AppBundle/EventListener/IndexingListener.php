<?php

namespace AppBundle\EventListener;

use AppBundle\Service\IndexingService;
use Doctrine\ORM\Event\LifecycleEventArgs;

class IndexingListener
{
    private $indexingService;

    public function __construct(IndexingService $indexingService)
    {
        $this->indexingService = $indexingService;
    }

    public function prePersist(LifecycleEventArgs $arg)
    {
        $this->indexingService->index($arg->getEntity());
    }

    public function preUpdate(LifecycleEventArgs $arg)
    {
        $this->prePersist($arg);
    }
}

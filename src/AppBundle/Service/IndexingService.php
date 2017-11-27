<?php

namespace AppBundle\Service;

use AppBundle\Entity\Item;

class IndexingService
{
    public function index($entity)
    {
        if ($entity instanceof Item) {
            $query = strip_tags(implode(' ', [
                $entity->getTitle(),
                $entity->getDescription(),
                $entity->getSummary(),
            ]));
            $entity->setQuery($query);
        }
    }
}

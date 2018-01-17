<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

interface EntitySearchInterface
{
    public function search($resourceClass, array $query);
}

<?php

namespace AppBundle\Service;

interface EntitySearchInterface
{
    public function search($resourceClass, array $query);
}

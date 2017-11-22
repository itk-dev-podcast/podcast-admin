<?php

namespace AppBundle\Entity;

use DoctrineExtensions\Taggable\Taggable;

interface CategorizableInterface extends Taggable
{
    public function getCategories();
}

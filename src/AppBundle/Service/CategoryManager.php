<?php

namespace AppBundle\Service;

use AppBundle\Entity\Taxonomy\Category;

class CategoryManager extends AbstractTaxonomyManager
{
    protected $taxonomyClass = Category::class;
}

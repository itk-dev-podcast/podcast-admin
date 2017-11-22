<?php

namespace AppBundle\Service;

use AppBundle\Entity\Categorization;
use AppBundle\Entity\Category;

class CategoryManager extends AbstractTagManager
{
    protected $tagClass = Category::class;
    protected $taggingClass = Categorization::class;
    protected $getTagsMethod = 'getCategories';
    protected $taggingName = 'categorization';
    protected $getTaggableTypeMethod = 'getCategorizableType';
    protected $getTaggableIdMethod = 'getCategorizableId';
}

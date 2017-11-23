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

    public function loadOrCreateCategory($name)
    {
        return $this->loadOrCreateTag($name);
    }

    public function loadOrCreateCategories($names)
    {
        return $this->loadOrCreateTags($names);
    }

    public function replaceCategories(array $tags, $resource)
    {
        $this->replaceTags($tags, $resource);
    }

    public function saveCategorization($resource)
    {
        $this->saveTagging($resource);
    }
}

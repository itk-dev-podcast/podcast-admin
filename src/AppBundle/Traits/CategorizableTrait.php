<?php

namespace AppBundle\Traits;

use AppBundle\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait CategorizableTrait
{
    use TaggableTrait;

    /**
     * @var ArrayCollection
     *
     * @ORM\Column(type="tag_list", nullable=true)
     */
    private $categoryList;

    private $categories;

    public function setCategories(ArrayCollection $categories)
    {
        $this->categories = $categories;
        // This ensures that the entity is dirty when doctrine persists it.
        $this->categoryList = $categories->map(function (Category $category) {
            return $category->getName();
        });

        return $this;
    }

    public function getCategories()
    {
        $this->categories = $this->categories ?: new ArrayCollection();

        return $this->categories;
    }

    public function getCategorizableType()
    {
        return __CLASS__;
    }

    public function getCategorizableId()
    {
        return $this->getId();
    }
}

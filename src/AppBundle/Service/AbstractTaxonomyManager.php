<?php

namespace AppBundle\Service;

use AppBundle\Entity\Taxonomy\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class AbstractTaxonomyManager
{
    protected $taxonomyClass;
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        if ($this->taxonomyClass === null) {
            throw new \Exception('Taxonomy class not defined');
        } elseif (!class_exists($this->taxonomyClass)) {
            throw new \Exception('Class '.$this->taxonomyClass.' does not exist');
        }
    }

    public function loadOrCreateTerms($names)
    {
        $existingTerms = $this->entityManager->getRepository($this->taxonomyClass)->findBy(['name' => $names]);
        $missingNames = array_diff($names, array_map(function ($term) {
            return $term->getName();
        }, $existingTerms));
        foreach ($missingNames as $name) {
            $term = new $this->taxonomyClass();
            $term->setName($name);
            $this->entityManager->persist($term);
            $existingTerms[] = $term;
        }

        return new ArrayCollection($existingTerms);
    }
}

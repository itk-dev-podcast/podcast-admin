<?php

namespace AppBundle\Service;

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

    public function loadOrCreateTerm($term)
    {
        $terms = $this->loadOrCreateTerms(is_scalar($term) ? [$term] : $term);

        return count($terms) === 1 ? $terms[0] : null;
    }

    public function loadOrCreateTerms(array $terms)
    {
        $names = array_filter($this->isAssoc($terms) ? array_keys($terms) : $terms);
        $existingTerms = $this->entityManager->getRepository($this->taxonomyClass)->findBy(['name' => $names]);
        $missingNames = array_diff($names, array_map(function ($term) {
            return $term->getName();
        }, $existingTerms));
        foreach ($missingNames as $name) {
            $term = new $this->taxonomyClass();
            $term->setName($name);
            if (isset($terms[$name])) {
                $term->setData($terms[$name]);
            }
            $this->entityManager->persist($term);
            $existingTerms[] = $term;
        }

        return new ArrayCollection($existingTerms);
    }

    // @see https://stackoverflow.com/a/173479
    protected function isAssoc(array $arr)
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

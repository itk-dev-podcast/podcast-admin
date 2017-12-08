<?php

namespace AppBundle\Service;

use AppBundle\Entity\Taxonomy\RelatedMaterial;
use Doctrine\Common\Collections\ArrayCollection;

class RelatedMaterialManager extends AbstractTaxonomyManager
{
    protected $taxonomyClass = RelatedMaterial::class;

    public function loadOrCreateTerms(array $terms)
    {
        $names = array_filter($this->isAssoc($terms) ? array_keys($terms) : $terms);
        $existingTerms = $this->entityManager->getRepository($this->taxonomyClass)->findBy(['id' => $names]);
        $missingNames = array_diff($names, array_map(function ($term) {
            return $term->getName();
        }, $existingTerms));
        foreach ($missingNames as $name) {
            $term = new $this->taxonomyClass();
            $term->setId($name)
                ->setName($name);
            if (isset($terms[$name])) {
                $term->setData($terms[$name]);
            }
            $this->entityManager->persist($term);
            $existingTerms[] = $term;
        }

        return new ArrayCollection($existingTerms);
    }
}

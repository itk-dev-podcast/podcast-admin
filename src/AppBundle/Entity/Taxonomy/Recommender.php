<?php

namespace AppBundle\Entity\Taxonomy;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"name"})
 * @ApiResource(
 *   collectionOperations={"get"={"method"="GET"}},
 *   itemOperations={"get"={"method"="GET"}},
 *   attributes={
 *     "normalization_context"={"groups"={"read"}}
 *   }
 * )
 */
class Recommender extends AbstractTaxonomy implements SoftDeleteable
{
    use SoftDeleteableEntity;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Item", mappedBy="recommenders")
     */
    protected $items;
}

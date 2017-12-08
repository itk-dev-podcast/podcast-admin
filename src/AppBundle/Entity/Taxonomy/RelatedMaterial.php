<?php

namespace AppBundle\Entity\Taxonomy;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@UniqueConstraint(name="id_unique",columns={"id"})})
 * @UniqueEntity(fields={"name"})
 */
class RelatedMaterial extends AbstractTaxonomyTerm implements SoftDeleteable
{
    use SoftDeleteableEntity;

    /**
     * @var string
     *
     * @Groups({"read"})
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Item", mappedBy="relatedMaterials")
     */
    protected $items;

    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }
}

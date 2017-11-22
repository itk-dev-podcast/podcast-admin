<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use FPN\TagBundle\Entity\Tagging as BaseTagging;

/**
 * @ORM\Table(uniqueConstraints={@UniqueConstraint(name="categorization_idx", columns={"tag_id", "resource_type", "resource_id"})})
 *
 * @ORM\Entity
 */
class Categorization extends BaseTagging
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="categorization")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     **/
    protected $tag;
}

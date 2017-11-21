<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FPN\TagBundle\Entity\Tag;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Table()
 *
 * @ORM\Entity(repositoryClass="DoctrineExtensions\Taggable\Entity\TagRepository")
 */
class Category extends Tag implements SoftDeleteable
{
    use SoftDeleteableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Groups({"read"})
     */
    protected $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     */
    protected $slug;

    /**
     * @var Categorization
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Categorization", mappedBy="tag", fetch="EAGER")
     **/
    protected $categorization;
}

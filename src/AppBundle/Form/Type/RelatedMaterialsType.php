<?php

namespace AppBundle\Form\Type;

use AppBundle\Service\RelatedMaterialManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RelatedMaterialsType extends AbstractType implements DataTransformerInterface
{
    private $manager;

    public function __construct(RelatedMaterialManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer($this);
    }

    public function transform($value)
    {
        return $value;
        header('Content-type: text/plain');
        echo var_export($value, true);
        die(__FILE__.':'.__LINE__.':'.__METHOD__);
        // ArrayCollection $value
        return $value->map(function ($term) {
            return $term->getName();
        })->toArray();
    }

    public function reverseTransform($value)
    {
        $data = json_decode($value, true);
        if (!is_array($data)) {
            return null;
        }

        return $this->manager->loadOrCreateTerm([$data['id'] => $data]);
    }

    public function getParent()
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_related_materials';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}

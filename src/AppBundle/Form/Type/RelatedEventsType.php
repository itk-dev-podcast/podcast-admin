<?php

namespace AppBundle\Form\Type;

use AppBundle\Service\RelatedEventManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RelatedEventsType extends AbstractType implements DataTransformerInterface
{
    private $manager;

    public function __construct(RelatedEventManager $manager)
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
        return 'app_related_events';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}

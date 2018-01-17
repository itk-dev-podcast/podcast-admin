<?php

namespace AppBundle\Serializer\JsonLd;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use AppBundle\Entity\Collection;
use AppBundle\Entity\Item;
use AppBundle\Service\EntitySearch;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class Normalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private $decorated;
    private $entitySearch;

    public function __construct(NormalizerInterface $decorated, EntitySearch $entitySearch)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->decorated = $decorated;
        $this->entitySearch = $entitySearch;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $items = null;
        if ($object instanceof Collection && $object->getItemQuery()) {
            $items = $this->entitySearch->search(Item::class, $object->getItemQuery());
            if ($items instanceof Paginator) {
                $items = $items->getIterator()->getArrayCopy();
            }
            $object->setItems(new ArrayCollection($items));
        }
        $data = $this->decorated->normalize($object, $format, $context);

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return $this->decorated->denormalize($data, $class, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        if($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}

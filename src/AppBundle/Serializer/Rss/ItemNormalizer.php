<?php

namespace AppBundle\Serializer\Rss;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Api\ResourceClassResolverInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class ItemNormalizer extends AbstractItemNormalizer
{
    const FORMAT = 'rss';

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return self::FORMAT === $format && parent::supportsNormalization($data, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
//        header('Content-type: text/plain'); echo var_export(null, true); die(__FILE__.':'.__LINE__.':'.__METHOD__);
        return $object;
//        return __FILE__;
//        header('Content-type: text/plain'); echo var_export(null, true); die(__FILE__.':'.__LINE__.':'.__METHOD__);
//        $context['cache_key'] = $this->getHalCacheKey($format, $context);
//        $resourceClass = $this->resourceClassResolver->getResourceClass($object, $context['resource_class'] ?? null, true);
//        $context = $this->initContext($resourceClass, $context);
//        $context['iri'] = $this->iriConverter->getIriFromItem($object);
//        $context['api_normalize'] = true;
//
//        $rawData = parent::normalize($object, $format, $context);
//        if (!is_array($rawData)) {
//            return $rawData;
//        }
//
//        $data = ['_links' => ['self' => ['href' => $context['iri']]]];
//        $components = $this->getComponents($object, $format, $context);
//        $data = $this->populateRelation($data, $object, $format, $context, $components, 'links');
//        $data = $this->populateRelation($data, $object, $format, $context, $components, 'embedded');
//
//        return $data + $rawData;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        throw new RuntimeException(sprintf('%s is a read-only format.', self::FORMAT));
    }
}

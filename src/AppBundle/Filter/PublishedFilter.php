<?php

namespace AppBundle\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use AppBundle\Entity\Event;
use AppBundle\Entity\Item;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class PublishedFilter extends AbstractFilter
{
    private $property = 'published';

    protected function extractProperties(Request $request): array
    {
        $properties = $request->query->all();

        if (!array_key_exists($this->property, $properties)) {
            $properties[$this->property] = true;
        }

        return $properties;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($property !== $this->property) {
            return;
        }

        $resource = new $resourceClass();
        if (!$resource instanceof Item) {
            return;
        }

        $value = strcasecmp($value, 'false') !== 0 && boolval($value);
        $alias = 'o';
        $valueParameter = $queryNameGenerator->generateParameterName($property);
        $queryBuilder
            ->andWhere(sprintf('%s.publishedAt <= :%s', $alias, $valueParameter))
            ->setParameter($valueParameter, new \DateTime('now', new \DateTimeZone('UTC')));
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass) : array
    {
        return [
            'published' => [
                'property' => 'published',
                'type' => 'boolean',
                'required' => false,
            ]
        ];
    }
}

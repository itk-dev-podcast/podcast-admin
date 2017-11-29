<?php

namespace AppBundle\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use AppBundle\Entity\Item;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GeolocationFilter extends AbstractFilter
{
    // geolocation[origin]=123.45,56.78&geolocation[radius]=10

    private $property = 'geolocation';
    private $alias = 'geolocation';
    private $radius = 10;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack, LoggerInterface $logger = null, array $properties = null)
    {
        $properties += [
            'lat' => 'lat',
            'lng' => 'lng',
            'radius' => 'radius',
        ];
        parent::__construct($managerRegistry, $requestStack, $logger, $properties);
        $this->property = isset($this->properties['property']) ? $this->properties['property'] : 'geolocation';
        $this->alias = isset($this->properties['alias']) ? $this->properties['alias'] : 'geolocation';
        $this->radius = (int) (isset($this->properties['radius']) ? $this->properties['radius'] : 10);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass): array
    {
        header('Content-type: text/plain');
        echo var_export($this->properties, true);
        die(__FILE__.':'.__LINE__.':'.__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($property !== $this->alias || !is_array($value)) {
            return;
        }

        if (isset($value['lat'], $value['lng'])) {
            $lat = $value['lat'];
            $lng = $value['lng'];
        } elseif (isset($value['origin'])) {
            list($lat, $lng) = explode(',', $value['origin']);
        } else {
            return;
        }

        $radius = max((int) (isset($value['radius']) ? $value['radius'] : $this->radius), 10);

        $resource = new $resourceClass();
        if (!$resource instanceof Item) {
            return;
        }

        // @see https://www.plumislandmedia.net/mysql/haversine-mysql-nearest-loc/
        $alias = 'o';
        $latParameter = $queryNameGenerator->generateParameterName($this->properties['lat']);
        $cosRadLatParameter = $queryNameGenerator->generateParameterName('cosRadLatParameter');
        $lngParameter = $queryNameGenerator->generateParameterName($this->properties['lng']);
        $radiusParameter = $queryNameGenerator->generateParameterName($this->properties['radius']);
        $queryBuilder
            ->andWhere(sprintf('%s.%s is not null', $alias, $this->properties['lat']))
            ->andWhere(sprintf('%s.%s is not null', $alias, $this->properties['lng']))
            ->andWhere(sprintf(
                '%s.%s between :%s - (:%s / 111.045) and :%s + (:%s / 111.045)',
                $alias,
                $this->properties['lat'],
                $latParameter,
                $radiusParameter,
                $latParameter,
                $radiusParameter
            ))
            ->andWhere(sprintf(
                '%s.%s between :%s - (:%s / (111.045 * :%s)) and :%s + (:%s / (111.045 * :%s))',
                $alias,
                $this->properties['lng'],
                $lngParameter,
                $radiusParameter,
                $cosRadLatParameter,
                $lngParameter,
                $radiusParameter,
                $cosRadLatParameter
            ))
            ->setParameter($latParameter, $lat)
            ->setParameter($cosRadLatParameter, cos(deg2rad($lat)))
            ->setParameter($lngParameter, $lng)
            ->setParameter($radiusParameter, $radius);
    }
}

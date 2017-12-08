<?php

namespace AppBundle\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

class DurationFilter extends RangeFilter
{
    protected function filterProperty(string $property, $values, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        foreach ($values as $key => &$value) {
            switch ($key) {
                case 'lt':
                case 'gt':
                case 'lte':
                case 'gte':
                case 'between':
                $value = $this->parseDuration($value);

                break;
            }
        }

        return parent::filterProperty($property, $values, $queryBuilder, $queryNameGenerator, $resourceClass, $operationName);
    }

    private function parseDuration($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        if (strpos($value, '..') !== false) {
            $tokens = preg_split('/\.\./', $value);

            return $this->parseDuration($tokens[0]).'..'.$this->parseDuration($tokens[1]);
        }

        if (preg_match('/(\\d+)(?::(\\d+))/', $value)) {
            $tokens = array_map('intval', array_reverse(preg_split('/:/', $value)));

            return $tokens[0]
                + 60 * (count($tokens) > 1 ? $tokens[1] : 0)
                + 60 * 60 * (count($tokens) > 2 ? $tokens[2] : 0);
        }

        return $value;
    }
}

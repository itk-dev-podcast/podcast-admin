<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Item;
use AppBundle\Entity\Taxonomy\Audience;
use AppBundle\Entity\Taxonomy\Context;
use AppBundle\Entity\Taxonomy\Recommender;
use AppBundle\Entity\Taxonomy\Subject;
use Symfony\Component\Yaml\Yaml;

class LoadEnrichment extends LoadData
{
    public function getOrder()
    {
        return 10;
    }

    protected function loadItem($data)
    {
        foreach ($data as $key => $items) {
            switch ($key) {
                case Subject::class:
                case Recommender::class:
                case Context::class:
                case Audience::class:
                    foreach ($items as $item) {
                        $term = (new $key())->setName($item['name']);
                        $this->manager->persist($term);
                    }
                    $this->manager->flush();

                    break;
                case 'items':
                    $repository = $this->manager->getRepository(Item::class);
                    foreach ($items as $item) {
                        $entity = $repository->findOneBy($item['item']);
                        if ($entity === null) {
                            throw new \Exception('Invalid item: '.Yaml::dump($item['item']));
                        }
                        unset($item['item']);

                        foreach (['subjects', 'recommenders', 'contexts', 'audiences'] as $property) {
                            if (isset($item[$property])) {
                                list($className, $methodName) = [
                                    'subjects' => [Subject::class, 'addSubject'],
                                    'recommenders' => [Recommender::class, 'addRecommender'],
                                    'contexts' => [Context::class, 'addContext'],
                                    'audiences' => [Audience::class, 'addAudience'],
                                ][$property];
                                foreach ($this->manager->getRepository($className)->findBy(['name' => (array) $item[$property]]) as $term) {
                                    $entity->{$methodName}($term);
                                }
                            }
                            $this->manager->persist($entity);
                        }
                        if (isset($item['publishedAt'])) {
                            $entity->setPublishedAt(new \DateTime($item['publishedAt']));
                        }
                    }

                    break;
                default:
                    throw new \Exception('Unknown key: '.$key);
            }
        }
    }
}

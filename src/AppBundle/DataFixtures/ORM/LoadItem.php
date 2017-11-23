<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Feed;
use AppBundle\Entity\Item;
use AppBundle\Entity\Taxonomy\Subject;
use AppBundle\Service\CategoryManager;
use Doctrine\Common\Collections\ArrayCollection;

class LoadItem extends LoadData
{
    public function getOrder()
    {
        return 4;
    }

    protected function loadItem($data)
    {
        $data['feed'] = $this->manager->getRepository(Feed::class)->findOneBy($data['feed']);
        if (isset($data['categories'])) {
            $categoryManager = $this->container->get(CategoryManager::class);
            $data['categories'] = new ArrayCollection($categoryManager->loadOrCreateTags($data['categories']));
        }
        if (isset($data['subjects'])) {
            $data['subjects'] = new ArrayCollection($this->manager->getRepository(Subject::class)->findBy(['name' => $data['subjects']]));
        }
        $item = $this->setValues(new Item(), $data);
        $this->persist($item);
    }
}

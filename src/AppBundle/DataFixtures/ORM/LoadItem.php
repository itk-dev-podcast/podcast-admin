<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Feed;
use AppBundle\Entity\Item;

class LoadItem extends LoadData
{
    public function getOrder()
    {
        return 3;
    }

    protected function loadItem($data)
    {
        $data['feed'] = $this->manager->getRepository(Feed::class)->findOneBy($data['feed']);
        $item = $this->setValues(new Item(), $data);
        $this->persist($item);
    }
}

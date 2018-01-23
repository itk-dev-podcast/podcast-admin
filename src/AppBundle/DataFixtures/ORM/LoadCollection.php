<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Collection;
use AppBundle\Entity\Item;

class LoadCollection extends LoadData
{
    public function getOrder()
    {
        return 3;
    }

    protected function loadItem($data)
    {
        if (isset($data['items'])) {
            $data['items'] = $this->manager->getRepository(Item::class)->findBy(['guid' => $data['items']]);
        }
        // header('Content-type: text/plain');
        // echo var_export($data, true); die(__FILE__.':'.__LINE__.':'.__METHOD__);

        $collection = $this->setValues(new Collection(), $data);
        $this->persist($collection);
    }
}

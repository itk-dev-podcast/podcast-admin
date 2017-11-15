<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Feed;

class LoadFeed extends LoadData
{
    public function getOrder()
    {
        return 2;
    }

    protected function loadItem($data)
    {
        $source = new Feed();
        $source = $this->setValues($source, $data);
        $this->persist($source);
    }
}

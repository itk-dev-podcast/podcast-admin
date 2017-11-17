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
        $feed = $this->setValues(new Feed(), $data);
        $this->persist($feed);
    }
}

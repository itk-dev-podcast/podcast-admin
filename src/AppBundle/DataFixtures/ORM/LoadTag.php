<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Feed;

class LoadTag extends LoadData
{
    public function getOrder()
    {
        return 4;
    }

    protected function loadItem($data)
    {
        $this->container->get('fpn_tag.tag_manager')->loadOrCreateTag($data['name']);
    }
}

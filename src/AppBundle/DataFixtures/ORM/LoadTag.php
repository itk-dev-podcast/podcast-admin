<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Tag;

class LoadTag extends LoadData
{
    public function getOrder()
    {
        return 3;
    }

    protected function loadItem($data)
    {
        $tag = $this->setValues(new Tag(), $data);
        $this->persist($tag);
    }
}

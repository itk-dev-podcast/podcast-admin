<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Source;

class LoadSource extends LoadData
{
    public function getOrder()
    {
        return 2;
    }

    protected function loadItem($data)
    {
        $source = new Source();
        $source = $this->setValues($source, $data);
        $this->persist($source);
    }
}

<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Subject;

class LoadSubject extends LoadData
{
    public function getOrder()
    {
        return 3;
    }

    protected function loadItem($data)
    {
        $subject = $this->setValues(new Subject(), $data);
        $this->persist($subject);
    }
}

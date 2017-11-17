<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;

class LoadUser extends LoadData
{
    public function getOrder()
    {
        return 1;
    }

    protected function loadItem($data)
    {
        $user = $this->setValues(new User(), $data);
        $this->container->get('fos_user.user_manager')->updateUser($user);
    }
}

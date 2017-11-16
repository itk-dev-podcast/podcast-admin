<?php

namespace AppBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Itk\Bundle\EasyAdminUserBundle\Traits\EasyAdminControllerUserManager;

class AdminController extends BaseAdminController
{
    use EasyAdminControllerUserManager;
}

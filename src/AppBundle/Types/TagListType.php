<?php

namespace AppBundle\Types;

use Doctrine\DBAL\Types\ArrayType;

class TagListType extends ArrayType
{
    public function getName()
    {
        return 'tag_list';
    }
}

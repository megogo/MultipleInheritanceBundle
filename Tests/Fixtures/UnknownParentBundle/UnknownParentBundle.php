<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\Tests\Fixtures\UnknownParentBundle;


use Megogo\Bundle\MultipleInheritanceBundle\Tests\Fixtures\BaseBundle;

class UnknownParentBundle extends BaseBundle
{

    public function getParent()
    {
        return 'CatchMeIfYouCanBundle';
    }

}
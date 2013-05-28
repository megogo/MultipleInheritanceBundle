<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\UnknownParentBundle;


use Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\BaseBundle;

class UnknownParentBundle extends BaseBundle
{

    public function getParent()
    {
        return 'CatchMeIfYouCanBundle';
    }

}
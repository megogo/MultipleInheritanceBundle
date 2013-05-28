<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\Child2Bundle;


use Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\BaseBundle;

class Child2Bundle extends BaseBundle
{
    public function getParent()
    {
        return 'ParentBundle';
    }

}
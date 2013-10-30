<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\Tests\Fixtures\Child1Bundle;


use Megogo\Bundle\MultipleInheritanceBundle\Tests\Fixtures\BaseBundle;

class Child1Bundle extends BaseBundle
{
    public function getParent()
    {
        return 'ParentBundle';
    }

}
<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\Tests\Fixtures;


use Symfony\Component\HttpKernel\Bundle\Bundle;

abstract class BaseBundle extends Bundle
{

    function __toString()
    {
        return $this->getName();
    }

}
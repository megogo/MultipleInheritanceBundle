<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\Child1Bundle\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class Child1Extension extends Extension
{

    public function load(array $config, ContainerBuilder $container)
    {
    }
}
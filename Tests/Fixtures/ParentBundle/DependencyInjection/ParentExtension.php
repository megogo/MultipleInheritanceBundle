<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\Tests\Fixtures\ParentBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ParentExtension extends Extension
{

    public function load(array $config, ContainerBuilder $container)
    {
    }
}
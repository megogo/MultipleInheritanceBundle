<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class TemplatingHelpersOverridePass implements CompilerPassInterface {

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('templating.helper.actions')) {
            $definition = $container->getDefinition('templating.helper.actions');

            $definition
                ->setClass('Megogo\Bundle\MultipleInheritanceBundle\Templating\Helper\ActionsHelper')
                ->addArgument(new Reference('controller_name_converter', ContainerInterface::IGNORE_ON_INVALID_REFERENCE, false));
        }
    }
}
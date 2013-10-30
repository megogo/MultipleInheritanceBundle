<?php


namespace Megogo\Bundle\MultipleInheritanceBundle;


use Megogo\Bundle\MultipleInheritanceBundle\DependencyInjection\Compiler\TemplatingHelpersOverridePass;
use Megogo\Bundle\MultipleInheritanceBundle\DependencyInjection\Compiler\TemplatingPathsCacheWarmerDisablingPass;
use Megogo\Bundle\MultipleInheritanceBundle\HttpKernel\BundleInheritanceKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

class MultipleInheritanceBundle extends Bundle
{


    function __construct(KernelInterface $kernel)
    {
        if (!$kernel instanceof BundleInheritanceKernel) {
            throw new \InvalidArgumentException('Your kernel must be inherited from Megogo\Bundle\MultipleInheritanceBundle\HttpKernel\BundleInheritanceKernel');
        }
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TemplatingPathsCacheWarmerDisablingPass());
        $container->addCompilerPass(new TemplatingHelpersOverridePass());
    }


}

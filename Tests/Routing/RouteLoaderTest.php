<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\Tests\Routing;


use Megogo\Bundle\MultipleInheritanceBundle\Routing\Loader\InheritanceRouteLoader;
use Megogo\Bundle\MultipleInheritanceBundle\Routing\Loader\ReplacingRouteLoader;
use Megogo\Bundle\MultipleInheritanceBundle\Routing\RoutingAdditionsInterface;
use Megogo\Bundle\MultipleInheritanceBundle\Tests\TestCase;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Routing\Loader\PhpFileLoader;

class RouteLoaderTest extends TestCase
{

    public function testReplacingRouterLoader()
    {
        $kernel     = $this->buildKernel();
        $locator    = new FileLocator($kernel);
        $baseLoader = new PhpFileLoader($locator);
        $resolver   = new LoaderResolver(array($baseLoader));

        $prefix             = 'test';
        $bundleName         = 'NewBundle';
        $host               = 'test.example.com';
        $loader             = new ReplacingRouteLoader($resolver, $bundleName, $prefix, array(), array(), $host);
        $baseCollection     = $baseLoader->load('@ParentBundle/Resources/config/routing.php');
        $modifiedCollection = $loader->load('@ParentBundle/Resources/config/routing.php');

        $this->assertEquals($baseCollection->count(), $modifiedCollection->count());

        foreach ($baseCollection as $name => $baseRoute) {
            $this->assertTrue(null !== ($route = $modifiedCollection->get($prefix . '_' . $name)));

            $this->assertTrue($route->hasDefault(RoutingAdditionsInterface::ACTIVE_BUNDLE_ATTRIBUTE));
            $this->assertEquals($bundleName, $route->getDefault(RoutingAdditionsInterface::ACTIVE_BUNDLE_ATTRIBUTE));
            $this->assertEquals($host, $route->getHost());
        }
    }

    public function testInheritanceLoader()
    {
        $kernel = $this->buildKernel();

        $loader     = new InheritanceRouteLoader($kernel);
        $locator    = new FileLocator($kernel);
        $baseLoader = new PhpFileLoader($locator);
        $resolver   = new LoaderResolver(array($baseLoader));
        $loader->setResolver($resolver);

        $baseCollection = $baseLoader->load('@ParentBundle/Resources/config/routing.php');
        $collection     = $loader->load('');
        foreach ($baseCollection->all() as $name => $route) {
            $this->assertTrue(
                null !== ($route = $collection->get($this->child2Bundle->getRoutingPrefix() . '_' . $name))
            );

            $this->assertTrue($route->hasDefault(RoutingAdditionsInterface::ACTIVE_BUNDLE_ATTRIBUTE));
            $this->assertEquals(
                $this->child2Bundle->getName(),
                $route->getDefault(RoutingAdditionsInterface::ACTIVE_BUNDLE_ATTRIBUTE)
            );
            $this->assertEquals($this->child2Bundle->getHost(), $route->getHost());
        }

        $this->assertStringStartsWith(
            $this->child2Bundle->getNamespace(),
            $collection->get($this->child2Bundle->getRoutingPrefix() . '_home')->getDefault('_controller'),
            'Namespace in route of parent bundle must start with child bundle namespace'
        );
    }

}
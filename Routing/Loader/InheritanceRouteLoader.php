<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Routing\Loader;


use Igorynia\Bundle\MultipleInheritanceBundle\Routing\RoutingAdditionsInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollection;

class InheritanceRouteLoader extends Loader
{

    private $kernel;
    private $loaded = false;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @inheritdoc
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Don not add this loader twice');
        }

        $routes = new RouteCollection();

        foreach ($this->kernel->getBundles() as $name => $bundle) {
            if (!$bundle instanceof RoutingAdditionsInterface) {
                continue;
            }

            $loader = $this->buildReplacingLoader($name, $bundle);

            foreach ($bundle->getResourcesToOverride() as $resource) {
                $routes->addCollection($loader->load($this->kernel->locateResource($resource)));
            }
        }

        $this->loaded = true;

        return $routes;
    }

    /**
     * @param $bundleName
     * @param RoutingAdditionsInterface $routingAdditions
     * @return LoaderInterface
     */
    protected function buildReplacingLoader($bundleName, RoutingAdditionsInterface $routingAdditions)
    {
        return new ReplacingRouteLoader(
            $this->resolver,
            $bundleName,
            $routingAdditions->getRoutingPrefix(),
            $routingAdditions->getDefaults(),
            $routingAdditions->getRequirements(),
            $routingAdditions->getHost()
        );
    }

    /**
     * @inheritdoc
     */
    public function supports($resource, $type = null)
    {
        return 'inheritance' === $type;
    }
}
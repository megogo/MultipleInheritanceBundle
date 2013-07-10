<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Routing\Loader;


use Igorynia\Bundle\MultipleInheritanceBundle\HttpKernel\BundleInheritanceKernel;
use Igorynia\Bundle\MultipleInheritanceBundle\Routing\RoutingAdditionsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class InheritanceRouteLoader extends Loader
{

    private $kernel;
    private $loaded = false;

    private $parser;

    public function __construct(BundleInheritanceKernel $kernel)
    {
        $this->kernel = $kernel;
        $this->parser = new ControllerNameParser($kernel);
    }

    /**
     * @inheritdoc
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Don not add this loader twice');
        }

        $routes               = new RouteCollection();
        $previousActiveBundle = $this->kernel->getActiveBundle();

        foreach ($this->kernel->getBundles() as $name => $bundle) {
            if (!$bundle instanceof RoutingAdditionsInterface) {
                continue;
            }

            $this->kernel->setActiveBundle($bundle);

            $loader = $this->buildReplacingLoader($name, $bundle);
            $loader->setControllerNameParser($this->parser);

            foreach ($bundle->getResourcesToOverride() as $resource) {
                $routes->addCollection($loader->load($this->kernel->locateResource($resource)));
            }
        }

        $this->kernel->setActiveBundle($previousActiveBundle);

        $this->loaded = true;

        return $routes;
    }

    /**
     * @param $bundleName
     * @param RoutingAdditionsInterface $routingAdditions
     * @return ReplacingRouteLoader
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
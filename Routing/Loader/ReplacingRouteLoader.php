<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Routing\Loader;


use Igorynia\Bundle\MultipleInheritanceBundle\Routing\RoutingAdditionsInterface;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class ReplacingRouteLoader replaces bundle name, adds prefix to routes and replaces some options of already created
 * routes. Useful when you want copy routes from parent bundle, add some requirements, and replace paths to child bundle.
 *
 * @package Igorynia\Bundle\MultipleInheritanceBundle\Routing\Loader
 */
class ReplacingRouteLoader extends DelegatingLoader
{

    /**
     * @var
     */
    private $bundleName;
    /**
     * @var
     */
    private $routePrefix;
    /**
     * @var array
     */
    private $defaults;
    /**
     * @var array
     */
    private $requirements;
    /**
     * @var string
     */
    private $host;

    public function __construct(
        LoaderResolverInterface $resolver,
        $bundleName,
        $routePrefix,
        array $defaults = array(),
        array $requirements = array(),
        $host = null
    ) {
        parent::__construct($resolver);

        $this->bundleName   = $bundleName;
        $this->routePrefix  = $routePrefix;
        $this->defaults     = $defaults;
        $this->requirements = $requirements;
        $this->host         = $host;
    }

    public function load($resource, $type = null)
    {
        $baseRoutes = parent::load($resource, $type);
        $routes     = new RouteCollection();

        /**
         * @var Route $route
         */
        foreach ($baseRoutes->all() as $routeName => $route) {
            $route->addDefaults($this->defaults);
            $route->addRequirements($this->requirements);

            $route->setDefault(RoutingAdditionsInterface::ACTIVE_BUNDLE_ATTRIBUTE, $this->bundleName);

            $routes->add($this->routePrefix . '_' . $routeName, $route);
        }

        if (null !== $this->host) {
            $routes->setHost($this->host);
        }


        return $routes;
    }

}
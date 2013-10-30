<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\Routing\Loader;


use Megogo\Bundle\MultipleInheritanceBundle\Routing\RoutingAdditionsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class ReplacingRouteLoader replaces bundle name, adds prefix to routes and replaces some options of already created
 * routes. Useful when you want copy routes from parent bundle, add some requirements, and replace paths to child bundle.
 *
 * @package Megogo\Bundle\MultipleInheritanceBundle\Routing\Loader
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

    /**
     * @var ControllerNameParser|null
     */
    private $controllerNameParser = null;

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
            if ($route->hasDefault('allow_override') && false === $route->getDefault('allow_override')) {
                continue;
            }

            $route->addDefaults($this->defaults);
            $route->addRequirements($this->requirements);

            $route->setDefault(RoutingAdditionsInterface::ACTIVE_BUNDLE_ATTRIBUTE, $this->bundleName);

            if (null !== $this->controllerNameParser) {
                if ($controller = $route->getDefault('_controller')) {
                    try {
                        $controller = $this->controllerNameParser->parse($controller);
                    } catch (\InvalidArgumentException $ignore) {
//                         unable to optimize unknown notation
                    }

                    $route->setDefault('_controller', $controller);
                }
            }

            $routes->add($this->routePrefix . '_' . $routeName, $route);
        }

        if (null !== $this->host) {
            $routes->setHost($this->host);
        }


        return $routes;
    }

    /**
     * @param null|\Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser $controllerNameParser
     */
    public function setControllerNameParser($controllerNameParser)
    {
        $this->controllerNameParser = $controllerNameParser;
    }

    /**
     * @return null|\Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser
     */
    public function getControllerNameParser()
    {
        return $this->controllerNameParser;
    }

}
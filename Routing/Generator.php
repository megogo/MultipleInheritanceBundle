<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\Routing;

use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator as BaseUrlGenerator;

class Generator extends BaseUrlGenerator
{

    private $routePrefix = null;

    protected function doGenerate(
        $variables,
        $defaults,
        $requirements,
        $tokens,
        $parameters,
        $name,
        $referenceType,
        $hostTokens
    ) {
        if (null === $this->routePrefix) {
            $this->initRoutePrefix();
        }

        if (is_string($this->routePrefix)) {
            try {
                return $this->generate(sprintf('%s_%s', $this->routePrefix, $name), $parameters, $referenceType);
            } catch (RouteNotFoundException $ignore) {
            }
        }

        return parent::doGenerate(
            $variables,
            $defaults,
            $requirements,
            $tokens,
            $parameters,
            $name,
            $referenceType,
            $hostTokens
        );
    }

    protected function initRoutePrefix()
    {
        global $kernel;

        $kernel1 = $kernel;
        if ($kernel1 instanceof HttpCache) {
            $kernel1 = $kernel1->getKernel();
        }

        if ($kernel1->getActiveBundle() && $kernel1->getActiveBundle() instanceof RoutingAdditionsInterface) {
            $this->routePrefix = $kernel1->getActiveBundle()->getRoutingPrefix();
        } else {
            $this->routePrefix = false;
        }
    }

}
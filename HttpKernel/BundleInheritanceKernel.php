<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\HttpKernel;


use Megogo\Bundle\MultipleInheritanceBundle\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\HttpKernel\Kernel;

abstract class BundleInheritanceKernel extends Kernel
{

    /**
     * @var BundleInterface
     */
    private $activeBundle = null;

    /**
     * @return BundleInterface
     */
    public function getActiveBundle()
    {
        return $this->activeBundle;
    }

    public function setActiveBundle(BundleInterface $activeBundle = null)
    {
        $this->activeBundle = $activeBundle;
    }

    public function getBundle($name, $first = true)
    {
        if (null !== $this->activeBundle && $this->activeBundle->getParent()) {
            $bundles = parent::getBundle($this->activeBundle->getName(), false);

            if ($this->isBundleInArray($bundles, $name)) {
                return $first ? $bundles[0] : $bundles;
            }
        }

        return parent::getBundle($name, $first);
    }

    /**
     * @param BundleInterface[] $bundles
     * @param $bundleName
     * @return bool
     */
    private function isBundleInArray(array $bundles, $bundleName)
    {
        foreach ($bundles as $bundle) {
            if ($bundle->getName() === $bundleName) {
                return true;
            }
        }

        return false;
    }


    /**
     * Initializes the data structures related to the bundle management.
     *
     *  - the bundles property maps a bundle name to the bundle instance,
     *  - the bundleMap property maps a bundle name to the bundle inheritance hierarchy (most derived bundle first).
     */
    protected function initializeBundles()
    {
        // init bundles
        $this->bundles = array();

        /**
         * @var BundleInterface $bundle
         */
        foreach ($this->registerBundles() as $bundle) {
            $name = $bundle->getName();

            $this->bundles[$name]   = $bundle;
            $this->bundleMap[$name] = array($bundle);
        }

        foreach ($this->bundles as $name => $bundle) {
            while ($parentNames = $bundle->getParent()) {
                if (!is_array($parentNames)) {
                    $parentNames = array($parentNames);
                }
                $throwUnknownParentException = false;
                foreach ($parentNames as $parentName) {
                    if (!array_key_exists($parentName, $this->bundles)) {
                        $throwUnknownParentException = true;
                        continue;
                    }
                    $bundle = $this->bundles[$parentName];

                    array_push($this->bundleMap[$name], $bundle);
                    $throwUnknownParentException = false;
                    break;
                }

                if ($throwUnknownParentException) {
                    throw new \InvalidArgumentException(sprintf(
                        'Bundle "%s" declared his parent as one of "%s", which is unknown',
                        $name,
                        implode(', ', $parentNames)
                    ));
                }
            }
        }
    }

    /**
     * Returns a loader for the container.
     *
     * @param ContainerInterface $container The service container
     *
     * @return DelegatingLoader The loader
     */
    protected function getContainerLoader(ContainerInterface $container)
    {
        $locator  = new FileLocator($this);
        $resolver = new LoaderResolver(array(
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new ClosureLoader($container),
        ));

        return new DelegatingLoader($resolver);
    }

//    public function locateResource($name, $dir = null, $first = true)
//    {
//        $result = parent::locateResource($name, $dir, $first);
//
//        /**
//         * @var LoggerInterface $logger
//         */
//        if ($logger = $this->getContainer()->get('logger')) {
//            $logger->debug(sprintf('Loading resource "%s" from "%s"', $name, $result));
//        }
//
//        return $result;
//    }
}

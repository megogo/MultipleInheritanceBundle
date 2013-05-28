<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\HttpKernel;


use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

abstract class BundleInheritanceKernel extends Kernel {

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

            $this->bundles[$name] = $bundle;
        }

        foreach ($this->bundles as $name => $bundle) {
            $this->bundleMap[$name] = array($bundle);

            while ($parentName = $bundle->getParent()) {
                $bundle = $this->bundles[$parentName];

                $this->bundleMap[$name][] = $bundle;
            }
        }
    }

}
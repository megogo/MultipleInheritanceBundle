<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\HttpKernel;


use Symfony\Component\HttpKernel\Bundle\BundleInterface;
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
            while ($parentName = $bundle->getParent()) {
                if (!array_key_exists($parentName, $this->bundles)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Bundle "%s" declared his parent as "%s", which is unknown',
                        $name,
                        $parentName
                    ));
                }
                $bundle = $this->bundles[$parentName];

                array_push($this->bundleMap[$name], $bundle);
            }
        }
    }

}

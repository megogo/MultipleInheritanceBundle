<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures;


use Igorynia\Bundle\MultipleInheritanceBundle\HttpKernel\BundleInheritanceKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class TestKernel extends BundleInheritanceKernel
{

    protected $bundlesToRegister;

    public function __construct($environment, $debug, $bundles = array())
    {
        $this->bundlesToRegister = $bundles;

        parent::__construct($environment, $debug);
    }

    /**
     * @inheritdoc
     */
    public function registerBundles()
    {
        return $this->bundlesToRegister;
    }

    /**
     * @inheritdoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}
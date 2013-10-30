<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\HttpKernel\HttpCache;


use Megogo\Bundle\MultipleInheritanceBundle\HttpKernel\BundleInheritanceKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpCache\Store;

/**
 * Class StoreImpl injects active bundle in cache key
 *
 * @package Megogo\Bundle\MultipleInheritanceBundle\HttpKernel\HttpCache
 */
class StoreImpl extends Store
{

    protected $keyCache;

    /**
     * @var \Megogo\Bundle\MultipleInheritanceBundle\HttpKernel\BundleInheritanceKernel
     */
    private $kernel;

    function __construct(BundleInheritanceKernel $kernel, $rootPath)
    {
        parent::__construct($rootPath);

        $this->keyCache = new \SplObjectStorage();

        $this->kernel = $kernel;
    }

    protected function generateCacheKey(Request $request)
    {
        $bundleKey = '';
        if (null !== ($activeBundle = $this->kernel->getActiveBundle())) {
            $bundleKey = $activeBundle->getName();
        }

        return 'md' . hash('sha256', $request->getUri() . $bundleKey);
    }

}
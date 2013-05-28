<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\CacheWarmer;


use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Class DummyCacheWarmer used to replace some symfony cache warmers.
 *
 * @package Igorynia\Bundle\MultipleInheritanceBundle\CacheWarmer
 */
class DummyCacheWarmer implements CacheWarmerInterface {

    /**
     * @inheritdoc
     */
    public function isOptional()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function warmUp($cacheDir)
    {
        // Do nothing
    }
}
<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\HttpKernel\HttpCache;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache as BaseCache;

class HttpCache extends BaseCache
{

    /**
     * @inheritdoc
     */
    protected function createStore()
    {
        return new StoreImpl($this->kernel, $this->cacheDir ? : $this->kernel->getCacheDir() . '/http_cache');
    }

}
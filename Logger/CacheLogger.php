<?php
/**
 * @author Boris Guéry <guery.b@gmail.com>
 */

namespace Tbbc\CacheBundle\Logger;


use Tbbc\CacheBundle\Aop\Interceptor\CacheOperationContext;

class CacheLogger implements CacheLoggerInterface
{
    protected $cacheOperationContexts = array();

    public function log(CacheOperationContext $context)
    {
        $this->cacheOperationContexts[] = $context;
    }

    public function getCacheOperationContexts()
    {
        return $this->cacheOperationContexts;
    }
}

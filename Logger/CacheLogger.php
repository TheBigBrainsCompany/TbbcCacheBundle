<?php
/**
 * @author Boris Guéry <guery.b@gmail.com>
 */

namespace Kitano\CacheBundle\Logger;


use Kitano\CacheBundle\Aop\Interceptor\CacheOperationContext;

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

<?php
/**
 * @author Boris Guéry <guery.b@gmail.com>
 */

namespace Kitano\CacheBundle\Logger;


use Kitano\CacheBundle\Aop\Interceptor\CacheOperationContext;

interface CacheLoggerInterface
{
    /**
     * @param CacheOperationContext $context
     * @return mixed
     */
    public function log(CacheOperationContext $context);

    /**
     * @return array|CacheOperationContext[]
     */
    public function getCacheOperationContexts();
}

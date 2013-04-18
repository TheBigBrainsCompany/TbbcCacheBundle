<?php
/**
 * @author Boris Guéry <guery.b@gmail.com>
 */

namespace Kitano\CacheBundle\Logger;


use Kitano\CacheBundle\Aop\Interceptor\CacheOperationContext;

interface CacheLoggerInterface
{
    public function log(CacheOperationContext $context);

    public function getCacheOperationContexts();
}

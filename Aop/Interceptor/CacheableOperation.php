<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Aop\Interceptor;

use CG\Proxy\MethodInvocation;
use Kitano\CacheBundle\Exception\InvalidArgumentException;
use Kitano\CacheBundle\Metadata\CacheMethodMetadataInterface;
use Kitano\CacheBundle\Metadata\CacheableMethodMetadata;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
class CacheableOperation extends AbstractCacheOperation
{
    public function handle(CacheMethodMetadataInterface $methodMetadata, MethodInvocation $methodInvocation)
    {
        if (!$methodMetadata instanceof CacheableMethodMetadata) {

            throw new InvalidArgumentException(sprintf('%s does only support "CacheableMethodMetadata" objects', __CLASS__ ));
        }

        $cacheKey = $this->generateCacheKey($methodMetadata, $methodInvocation);

        $returnValue = null;
        foreach ($methodMetadata->caches as $cacheName) {
            if (null !== ($returnValue = $this->getCacheManager()->getCache($cacheName)->get($cacheKey))) {
                break;
            }
        }

        // Cache hit
        if (null !== $returnValue) {
            return $returnValue;
        }

        // Cache miss
        $returnValue = $methodInvocation->proceed();

        foreach ($methodMetadata->caches as $cacheName) {
            $this->getCacheManager()->getCache($cacheName)->set($cacheKey, $returnValue);
        }

        return $returnValue;
    }
}

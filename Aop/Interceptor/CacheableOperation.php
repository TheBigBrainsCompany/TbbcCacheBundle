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

        $this->cacheOperationContext->setTargetClass($methodMetadata->class);
        $this->cacheOperationContext->setTargetMethod($methodMetadata->name);

        $returnValue = false;
        foreach($methodMetadata->caches as $cacheName) {
            if ($returnValue = $this->getCacheManager()->getCache($cacheName)->get($cacheKey)) {
                break;
            }
        }

        // Cache hit
        if ($returnValue) {
            $this->cacheOperationContext
                ->addMessage(sprintf("Cache hit for '%s' in '%s'", $cacheKey, $cacheName))
            ;
            return $returnValue;
        }

        // Cache miss
        $this->cacheOperationContext
            ->addMessage(
                sprintf(
                    "Cache missed for '%' in '%s'",
                    $cacheKey,
                    trim(implode(', ', $methodMetadata->caches), ',')
                )
            )
        ;
        $returnValue = $methodInvocation->proceed();

        foreach($methodMetadata->caches as $cacheName) {
            $this->getCacheManager()->getCache($cacheName)->set($cacheKey, $returnValue);
            $this->cacheOperationContext
                ->addMessage(
                    sprintf(
                        "Set cache for '%' in '%s'",
                        $cacheKey,
                        $cacheName
                    )
                )
            ;
        }

        return $returnValue;
    }

    public function getOperationName()
    {
        return 'cacheable';
    }
}

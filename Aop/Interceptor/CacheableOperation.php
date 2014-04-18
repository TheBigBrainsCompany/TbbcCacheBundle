<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Aop\Interceptor;

use CG\Proxy\MethodInvocation;
use Tbbc\CacheBundle\CacheEvents;
use Tbbc\CacheBundle\Event\CacheHitEvent;
use Tbbc\CacheBundle\Event\CacheUpdateEvent;
use Tbbc\CacheBundle\Exception\InvalidArgumentException;
use Tbbc\CacheBundle\Metadata\CacheMethodMetadataInterface;
use Tbbc\CacheBundle\Metadata\CacheableMethodMetadata;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
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
        $this->cacheOperationContext->setCaches($methodMetadata->caches);

        $returnValue = null;
        foreach ($methodMetadata->caches as $cacheName) {
            if (null !== ($returnValue = $this->getCacheManager()->getCache($cacheName)->get($cacheKey))) {

                break;
            }
        }

        // Cache hit
        if (null !== $returnValue) {
            $this->cacheOperationContext->setCacheHit($cacheName);

            $event = new CacheHitEvent($methodMetadata, $cacheName, $cacheKey, $returnValue);
            $this->dispatcher->dispatch(CacheEvents::AFTER_CACHE_HIT, $event);

            return $returnValue;
        }

        // Cache miss
        $this->cacheOperationContext->setCacheMiss($methodMetadata->caches);
        $returnValue = $methodInvocation->proceed();

        foreach ($methodMetadata->caches as $cacheName) {
            $this->getCacheManager()->getCache($cacheName)->set($cacheKey, $returnValue);

            $this->cacheOperationContext->addCacheUpdate($cacheName);

            $event = new CacheUpdateEvent($methodMetadata, $cacheName, $cacheKey, $returnValue);
            $this->dispatcher->dispatch(CacheEvents::AFTER_CACHE_UPDATE, $event);
        }

        return $returnValue;
    }

    public function getOperationName()
    {
        return 'cacheable';
    }
}

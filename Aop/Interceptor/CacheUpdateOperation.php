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
use Tbbc\CacheBundle\Event\CacheUpdateEvent;
use Tbbc\CacheBundle\Exception\InvalidArgumentException;
use Tbbc\CacheBundle\Metadata\CacheMethodMetadataInterface;
use Tbbc\CacheBundle\Metadata\CacheUpdateMethodMetadata;

/**
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class CacheUpdateOperation extends AbstractCacheOperation
{
    public function handle(CacheMethodMetadataInterface $methodMetadata, MethodInvocation $methodInvocation)
    {
        if (!$methodMetadata instanceof CacheUpdateMethodMetadata) {
            throw new InvalidArgumentException(sprintf('%s does only support "CacheUpdateMethodMetadata" objects', __CLASS__ ));
        }

        $returnValue = $methodInvocation->proceed();

        $this->cacheOperationContext->setTargetClass($methodMetadata->class);
        $this->cacheOperationContext->setTargetMethod($methodMetadata->name);

        $cacheKey = $this->generateCacheKey($methodMetadata, $methodInvocation);

        // Updates all caches
        foreach($methodMetadata->caches as $cacheName) {
            $this->getCacheManager()->getCache($cacheName)->set($cacheKey, $returnValue);

            $this->cacheOperationContext->addCacheUpdate($cacheName);

            $event = new CacheUpdateEvent($methodMetadata, $cacheName, $cacheKey, $returnValue);
            $this->dispatcher->dispatch(CacheEvents::AFTER_CACHE_UPDATE, $event);
        }

        return $returnValue;
    }

    public function getOperationName()
    {
        return 'cache_update';
    }
}

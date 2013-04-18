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
use Kitano\CacheBundle\Metadata\CacheEvictMethodMetadata;
use Kitano\CacheBundle\Metadata\CacheMethodMetadataInterface;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
class CacheEvictOperation extends AbstractCacheOperation
{
    public function handle(CacheMethodMetadataInterface $methodMetadata, MethodInvocation $methodInvocation)
    {
        if (!$methodMetadata instanceof CacheEvictMethodMetadata) {

            throw new InvalidArgumentException(sprintf('%s does only support "CacheEvictMethodMetadata" objects', __CLASS__ ));
        }

        $returnValue = $methodInvocation->proceed();

        if ($methodMetadata->allEntries) {
            foreach ($methodMetadata->caches as $cacheName) {
                $this->getCacheManager()->getCache($cacheName)->flush();
            }
        } else {
            $cacheKey = $this->generateCacheKey($methodMetadata, $methodInvocation);

            foreach ($methodMetadata->caches as $cacheName) {
                $this->getCacheManager()->getCache($cacheName)->delete($cacheKey);
            }
        }

        return $returnValue;
    }
}

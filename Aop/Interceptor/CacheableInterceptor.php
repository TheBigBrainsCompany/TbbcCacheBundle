<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Aop\Interceptor;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Kitano\CacheBundle\Cache\CacheManagerInterface;
use Kitano\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface;
use Kitano\CacheBundle\Metadata\CacheMethodMetadataInterface;
use Kitano\CacheBundle\Metadata\MethodMetadata;
use Metadata\MetadataFactoryInterface;
use Pel\Expression\Expression;
use Pel\Expression\ExpressionCompiler;
use Pel\Expression\Compiler\ParameterExpressionCompiler;

/**
 * Class CacheableInterceptor
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 * @author Boris Guéry <guery.b@gmail.com>
 */
class CacheableInterceptor implements MethodInterceptorInterface
{
    const CACHEABLE = 'cacheable';
    const EVICT     = 'cache_evict';
    const UPDATE    = 'cache_update';

    private $metadataFactory;
    private $cacheManager;
    private $keyGenerator;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        CacheManagerInterface $cacheManager,
        KeyGeneratorInterface $keyGenerator
    )
    {
        $this->metadataFactory = $metadataFactory;
        $this->cacheManager = $cacheManager;
        $this->keyGenerator = $keyGenerator;
    }

    public function intercept(MethodInvocation $method)
    {
        $metadata = $this->metadataFactory->getMetadataForClass($method->reflection->class);

        // no cache metadata, proceed
        if (empty($metadata) || !isset($metadata->methodMetadata[$method->reflection->name])) {
            return $method->proceed();
        }

        $metadata = $metadata->methodMetadata[$method->reflection->name];

        if (!$metadata instanceof CacheMethodMetadataInterface) {
            return $method->proceed();
        }

        if (empty($metadata->caches)) {

           throw new \LogicException('No caches set');
        }

        if (self::CACHEABLE == $metadata->getOperation()) {

            $operation = new CacheableOperation($this->cacheManager, $this->keyGenerator);

            return $operation->handle($metadata, $method);
        }

        if (self::EVICT == $metadata->getOperation()) {

            $operation = new CacheEvictOperation($this->cacheManager, $this->keyGenerator);

            return $operation->handle($metadata, $method);
        }

        if (self::UPDATE == $metadata->getOperation()) {

            $operation = new CacheUpdateOperation($this->cacheManager, $this->keyGenerator);

            return $operation->handle($metadata, $method);
        }

        return $method->proceed();
    }
}

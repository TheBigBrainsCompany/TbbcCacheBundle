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

    private $metadataFactory;
    private $cacheManager;
    private $keyGenerator;
    private $expressionCompiler;

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
            return $this->handleCacheEvict($metadata, $method);
        }

        if (self::EVICT == $metadata->getOperation()) {
            return $this->handleCacheable($metadata, $method);
        }

        return $method->proceed();
    }

    protected function handleCacheEvict(MethodMetadata $metadata, MethodInvocation $method)
    {
        $returnValue = $method->proceed();

        if ($metadata->allEntries) {
            foreach($metadata->caches as $cacheName) {
                $this->cacheManager->getCache($cacheName)->flush();
            }
        } else {
            $cacheKey = $this->generateCacheKey($metadata, $method);

            foreach($metadata->caches as $cacheName) {
                $this->cacheManager->getCache($cacheName)->delete($cacheKey);
            }
        }

        return $returnValue;
    }

    protected function handleCacheable(MethodMetadata $metadata, MethodInvocation $method)
    {
        $cacheKey = $this->generateCacheKey($metadata, $method);

        $returnValue = false;
        foreach($metadata->caches as $cacheName) {
            if ($returnValue = $this->cacheManager->getCache($cacheName)->get($cacheKey)) {
                break;
            }
        }

        // Cache hit
        if ($returnValue) {
            return $returnValue;
        }

        // Cache miss
        $returnValue = $method->proceed();

        foreach($metadata->caches as $cacheName) {
            $this->cacheManager->getCache($cacheName)->set($cacheKey, $returnValue);
        }

        return $returnValue;
    }

    protected function generateCacheKey(MethodMetadata $metadata, MethodInvocation $method)
    {
        $keyGeneratorArguments = array();
        if (!empty($metadata->key)) {
            if ($metadata->key instanceof Expression) {
                if (null == $this->expressionCompiler) {
                    $this->expressionCompiler = new ExpressionCompiler();
                    $this->expressionCompiler->addTypeCompiler(new ParameterExpressionCompiler());
                }

                // TODO Add some cache here!
                $evaluator = eval($this->expressionCompiler->compileExpression($metadata->key));
                $key = call_user_func($evaluator, array('object' => $method));

                $keyGeneratorArguments[] = $key;
            }
        }

        if (empty($keyGeneratorArguments)) {
            $keyGeneratorArguments = $method->arguments;
        }

        return $this->keyGenerator->generateKey($keyGeneratorArguments);
    }
}

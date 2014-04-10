<?php

/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Aop\Interceptor;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tbbc\CacheBundle\Cache\CacheManagerInterface;
use Tbbc\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface;
use Tbbc\CacheBundle\Logger\CacheLoggerInterface;
use Tbbc\CacheBundle\Metadata\CacheMethodMetadataInterface;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CacheInterceptor
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 * @author Boris Guéry <guery.b@gmail.com>
 */
class CacheInterceptor implements MethodInterceptorInterface
{
    const CACHEABLE = 'cacheable';
    const EVICT     = 'cache_evict';
    const UPDATE    = 'cache_update';

    private $metadataFactory;
    private $cacheManager;
    private $keyGenerator;
    private $expressionLanguage;
    private $dispatcher;
    private $logger;

    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        CacheManagerInterface $cacheManager,
        KeyGeneratorInterface $keyGenerator,
        ExpressionLanguage $expressionLanguage,
        EventDispatcherInterface $dispatcher,
        CacheLoggerInterface $cacheLogger = null
    )
    {
        $this->metadataFactory    = $metadataFactory;
        $this->cacheManager       = $cacheManager;
        $this->keyGenerator       = $keyGenerator;
        $this->expressionLanguage = $expressionLanguage;
        $this->dispatcher         = $dispatcher;
        $this->logger             = $cacheLogger;
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

            $operation = new CacheableOperation(
                $this->cacheManager,
                $this->keyGenerator,
                $this->expressionLanguage,
                $this->dispatcher,
                $this->logger
            );

            return $operation->handle($metadata, $method);
        }

        if (self::EVICT == $metadata->getOperation()) {

            $operation = new CacheEvictOperation(
                $this->cacheManager,
                $this->keyGenerator,
                $this->expressionLanguage,
                $this->dispatcher,
                $this->logger
            );

            return $operation->handle($metadata, $method);
        }

        if (self::UPDATE == $metadata->getOperation()) {

            $operation = new CacheUpdateOperation(
                $this->cacheManager,
                $this->keyGenerator,
                $this->expressionLanguage,
                $this->dispatcher,
                $this->logger
            );

            return $operation->handle($metadata, $method);
        }

        return $method->proceed();
    }
}

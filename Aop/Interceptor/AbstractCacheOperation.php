<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Aop\Interceptor;

use CG\Proxy\MethodInvocation;
use Kitano\CacheBundle\Cache\CacheManagerInterface;
use Kitano\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface;
use Kitano\CacheBundle\Metadata\CacheMethodMetadataInterface;
use Pel\Expression\Expression;
use Pel\Expression\ExpressionCompiler;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
abstract class AbstractCacheOperation implements CacheOperationInterface
{
    private $cacheManager;
    private $keyGenerator;
    private $expressionCompiler;

    public function __construct(
        CacheManagerInterface $cacheManager,
        KeyGeneratorInterface $keyGenerator,
        ExpressionCompiler $expressionCompiler
    )
    {
        $this->cacheManager = $cacheManager;
        $this->keyGenerator = $keyGenerator;
        $this->expressionCompiler = $expressionCompiler;
    }

    protected function getCacheManager()
    {
        return $this->cacheManager;
    }

    protected function getKeyGenerator()
    {
        return $this->keyGenerator;
    }

    protected function generateCacheKey(CacheMethodMetadataInterface $metadata, MethodInvocation $method)
    {
        $keyGeneratorArguments = array();

        if (!empty($metadata->key)) {
            if ($metadata->key instanceof Expression) {
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

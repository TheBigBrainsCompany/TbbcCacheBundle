<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Aop\Interceptor;

use CG\Proxy\MethodInvocation;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tbbc\CacheBundle\Cache\CacheManagerInterface;
use Tbbc\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface;
use Tbbc\CacheBundle\Logger\CacheLoggerInterface;
use Tbbc\CacheBundle\Metadata\CacheMethodMetadataInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
abstract class AbstractCacheOperation implements CacheOperationInterface
{
    protected $cacheOperationContext;
    private $cacheManager;
    private $keyGenerator;
    protected $dispatcher;
    private $cacheLogger;

    public function __construct(
        CacheManagerInterface $cacheManager,
        KeyGeneratorInterface $keyGenerator,
        ExpressionLanguage $expressionLanguage,
        EventDispatcherInterface $dispatcher,
        CacheLoggerInterface $logger = null
    )
    {
        $this->cacheManager       = $cacheManager;
        $this->keyGenerator       = $keyGenerator;
        $this->expressionLanguage = $expressionLanguage;
        $this->dispatcher         = $dispatcher;
        $this->cacheLogger        = $logger;

        $this->cacheOperationContext = new CacheOperationContext($this->getOperationName());
    }

    protected function getCacheManager()
    {
        return $this->cacheManager;
    }

    protected function getKeyGenerator()
    {
        return $this->keyGenerator;
    }
    
    protected function getCacheLogger()
    {
        return $this->cacheLogger;
    }

    protected function generateCacheKey(CacheMethodMetadataInterface $metadata, MethodInvocation $method)
    {
        $keyGeneratorArguments = array();

        if (!empty($metadata->key)) {
            if ($metadata->key instanceof Expression) {
                // TODO Add some cache here!
                $values = array();
                foreach ($method->reflection->getParameters() as $param) {
                    $values[$param->name] = $method->getNamedArgument($param->name);
                }

                $key = $this->expressionLanguage->evaluate($metadata->key, $values);

                $keyGeneratorArguments[] = $key;
            }
        }

        if (empty($keyGeneratorArguments)) {
            $keyGeneratorArguments = $method->arguments;
        }

        $key = $this->keyGenerator->generateKey($keyGeneratorArguments);

        $this->cacheOperationContext->setKey($key);

        return $key;
    }

    abstract public function getOperationName();

    public function __destruct()
    {
        if (null !== $this->cacheLogger) {
            $this->cacheLogger->log($this->cacheOperationContext);
        }
    }
}

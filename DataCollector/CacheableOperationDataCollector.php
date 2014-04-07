<?php
/**
 * @author Boris Guéry <guery.b@gmail.com>
 */

namespace Tbbc\CacheBundle\DataCollector;


use Tbbc\CacheBundle\Logger\CacheLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class CacheableOperationDataCollector extends DataCollector
{
    private $cacheLogger;

    public function __construct(CacheLoggerInterface $cacheLogger)
    {
        $this->cacheLogger = $cacheLogger;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $cacheableOperations = array();
        $cacheEvictOperations = array();
        $cacheUpdateOperations = array();
        $hits = 0;
        $miss = 0;

        foreach($this->cacheLogger->getCacheOperationContexts() as $context) {
            if ('cacheable' == $context->getOperation()) {
                $cacheableOperations[] = $context;
                if (null !== $context->getCacheHit()) {
                    $hits++;
                } elseif (count($context->getCacheMiss())) {
                    $miss++;
                }
            }
            if ('cache_evict' == $context->getOperation()) {
                $cacheEvictOperations[] = $context;
            }
            if ('cache_update' == $context->getOperation()) {
                $cacheUpdateOperations[] = $context;
            }
        }

        $this->data = array(
            'hits' => $hits,
            'miss' => $miss,
            'operations' => $this->cacheLogger->getCacheOperationContexts(),
            'cacheableOperations' => $cacheableOperations,
            'cacheEvictOperations' => $cacheEvictOperations,
            'cacheUpdateOperations' => $cacheUpdateOperations,
        );
    }

    public function getHits()
    {
        return $this->data['hits'];
    }

    public function getMiss()
    {
        return $this->data['miss'];
    }

    public function getOperations()
    {
        return $this->data['operations'];
    }

    public function getCacheableOperations()
    {
        return $this->data['cacheableOperations'];
    }

    public function getCacheEvictOperations()
    {
        return $this->data['cacheEvictOperations'];
    }

    public function getCacheUpdateOperations()
    {
        return $this->data['cacheUpdateOperations'];
    }

    public function getName()
    {
        return 'tbbc_cache';
    }
}

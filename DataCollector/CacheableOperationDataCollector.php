<?php
/**
 * @author Boris Guéry <guery.b@gmail.com>
 */

namespace Kitano\CacheBundle\DataCollector;


use Kitano\CacheBundle\Logger\CacheLoggerInterface;
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
        $this->data = array(
            'operations' => $this->cacheLogger->getCacheOperationContexts(),
        );
    }

    public function getOperations()
    {
        return $this->data['operations'];
    }

    public function getName()
    {
        return 'cache_operations';
    }
}

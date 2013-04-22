<?php
/**
 * @author Boris Guéry <guery.b@gmail.com>
 */

namespace Kitano\CacheBundle\Aop\Interceptor;


final class CacheOperationContext
{
    /**
     * The operation name/type
     * One of 'cacheable', 'cache_evict', or 'cache_update'
     * @var string
     */
    private $operation;

    /**
     * The cache key that was generated for this operation
     * @var string
     */
    private $key;

    /**
     * Hit cache name
     * @var string
     */
    private $cacheHit;

    /**
     * Missed cache list
     * @var array
     */
    private $cacheMiss = array();

    /**
     * Cache list that were updated
     * @var array
     */
    private $cacheUpdates = array();

    /**
     * Cache name list target for this operation
     * @var array|string[]
     */
    private $caches = array();

    /**
     * Class name for which cache operation was executed
     * @var string
     */
    private $targetClass;

    /**
     * Method name for which cache operation was executed
     * @var string
     */
    private $targetMethod;

    /**
     * @var array|string[]
     */
    private $messages = array();

    /**
     * Whether or not this was a flush operation
     * @var bool
     */
    private $flush = false;

    /**
     * Whether or not the cache operation ran before method invocation
     * @var bool
     */
    private $beforeInvocation = false;


    /**
     * Constructor
     *
     * @param string $operation
     */
    public function __construct($operation)
    {
        $this->operation = $operation;
    }

    /**
     * Adds an info message for this operation
     *
     * @param string $message
     * @return CacheOperationContext
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Returns all the collected messages for this operation
     *
     * @return array|string[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Returns cache name list
     *
     * @return array|string[]
     */
    public function getCaches()
    {
        return $this->caches;
    }

    /**
     * Sets cache name list
     *
     * @param array $caches
     * @return CacheOperationContext
     */
    public function setCaches(array $caches)
    {
        $this->caches = $caches;

        return $this;
    }

    /**
     * Get generated key for this operation
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets generated key for this operation
     *
     * @param string $key
     * @return CacheOperationContext
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Returns operation name
     *
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @return string
     */
    public function getTargetClass()
    {
        return $this->targetClass;
    }

    /**
     * @param string $targetClass
     * @return CacheOperationContext
     */
    public function setTargetClass($targetClass)
    {
        $this->targetClass = $targetClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getTargetMethod()
    {
        return $this->targetMethod;
    }

    /**
     * @param string $targetMethod
     * @return CacheOperationContext
     */
    public function setTargetMethod($targetMethod)
    {
        $this->targetMethod = $targetMethod;

        return $this;
    }

    /**
     * @param string $hit
     * @return CacheOperationContext
     */
    public function setCacheHit($hit)
    {
        $this->cacheHit = $hit;

        return $this;
    }

    /**
     * @return string
     */
    public function getCacheHit()
    {
        return $this->cacheHit;
    }

    /**
     * @param array $miss
     * @return CacheOperationContext
     */
    public function setCacheMiss(array $miss)
    {
        $this->cacheMiss = $miss;

        return $this;
    }

    /**
     * @return array
     */
    public function getCacheMiss()
    {
        return $this->cacheMiss;
    }

    /**
     * @param string $name
     * @return CacheOperationContext
     */
    public function addCacheUpdate($name)
    {
        $this->cacheUpdates[] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getCacheUpdates()
    {
        return $this->cacheUpdates;
    }

    /**
     * @param boolean $flush
     * @return CacheOperationContext
     */
    public function setFlush($flush)
    {
        $this->flush = (bool) $flush;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isFlush()
    {
        return $this->flush;
    }

    /**
     * @param boolean $beforeInvocation
     * @return CacheOperationContext
     */
    public function setBeforeInvocation($beforeInvocation)
    {
        $this->beforeInvocation = $beforeInvocation;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isBeforeInvocation()
    {
        return $this->beforeInvocation;
    }
}

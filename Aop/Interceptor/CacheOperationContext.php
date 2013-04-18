<?php
/**
 * @author Boris Guéry <guery.b@gmail.com>
 */

namespace Kitano\CacheBundle\Aop\Interceptor;


final class CacheOperationContext
{
    private $operation;
    private $key;
    private $caches = array();
    private $options = array();
    private $targetClass;
    private $targetMethod;
    private $messages = array();

    public function __construct($operation)
    {
        $this->operation = $operation;
    }

    public function addMessage($message)
    {
        $this->messages[] = $message;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getCaches()
    {
        return $this->caches;
    }

    public function setCaches($caches)
    {
        $this->caches = $caches;

        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getTargetClass()
    {
        return $this->targetClass;
    }

    public function setTargetClass($targetClass)
    {
        $this->targetClass = $targetClass;

        return $this;
    }

    public function getTargetMethod()
    {
        return $this->targetMethod;
    }

    public function setTargetMethod($targetMethod)
    {
        $this->targetMethod = $targetMethod;

        return $this;
    }
}

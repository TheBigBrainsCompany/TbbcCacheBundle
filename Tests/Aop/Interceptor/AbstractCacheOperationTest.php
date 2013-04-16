<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Tests\Aop\Interceptor;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
abstract class AbstractCacheOperationTest extends \PHPUnit_Framework_TestCase
{
    protected function getMethodInvocation()
    {
        $methodInvocation = $this->getMockBuilder('\CG\Proxy\MethodInvocation')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $methodInvocation;
    }

    protected function getCache()
    {
        return $this->getMockBuilder('\Kitano\CacheBundle\Cache\CacheInterface')
            ->getMock()
            ;
    }

    protected function getCacheManager()
    {
        return $this->getMockBuilder('\Kitano\CacheBundle\Cache\CacheManagerInterface')
            ->getMock()
            ;
    }

    protected function getKeyGenerator()
    {
        return $this->getMockBuilder('\Kitano\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface')
            ->getMock()
            ;
    }
}

<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Tests\Aop\Interceptor;

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
        return $this->getMockBuilder('\Tbbc\CacheBundle\Cache\CacheInterface')
            ->getMock()
            ;
    }

    protected function getCacheManager()
    {
        return $this->getMockBuilder('\Tbbc\CacheBundle\Cache\CacheManagerInterface')
            ->getMock()
            ;
    }

    protected function getKeyGenerator()
    {
        return $this->getMockBuilder('\Tbbc\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface')
            ->getMock()
            ;
    }

    protected function getExpressionCompiler()
    {
        return $this->getMockBuilder('\Pel\Expression\ExpressionCompiler')
            ->getMock()
            ;
    }

    protected function getEventDispatcher()
    {
        return $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->getMock()
            ;
    }
}

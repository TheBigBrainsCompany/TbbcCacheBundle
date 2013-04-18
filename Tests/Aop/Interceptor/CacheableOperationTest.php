<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Tests\Aop\Interceptor;

use Kitano\CacheBundle\Aop\Interceptor\CacheableOperation;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
class CacheableOperationTest extends AbstractCacheOperationTest
{
    /**
     * @expectedException \Kitano\CacheBundle\Exception\InvalidArgumentException
     */
    public function testHandleWithWrongMethodMetadataThrowsAnException()
    {
        $methodInvocation = $this->getMethodInvocation();
        $methodInvocation
            ->expects($this->never())
            ->method('proceed')
        ;

        $incorrectMethodMetadata = $this->getMockBuilder('Kitano\CacheBundle\Metadata\CacheMethodMetadataInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $operation = new CacheableOperation(
            $this->getCacheManager(),
            $this->getKeyGenerator(),
            $this->getExpressionCompiler()
        );

        $operation->handle($incorrectMethodMetadata, $this->getMethodInvocation());
    }

    public function testCacheableOperationProperlyGetValueFromCacheIfItExists()
    {
        $methodInvocation = $this->getMethodInvocation();
        $methodInvocation
            ->expects($this->never())
            ->method('proceed')
        ;

        $keyGenerator = $this->getKeyGenerator();
        $keyGenerator
            ->expects($this->once())
            ->method('generateKey')
            ->withAnyParameters()
            ->will($this->returnValue('cachedValue'))
        ;

        $cache = $this->getCache();
        $cache
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('cachedValue'))
            ->will($this->returnValue('cachedValue'))
        ;

        $cache
            ->expects($this->never())
            ->method('set')
        ;

        $cacheManager = $this->getCacheManager();
        $cacheManager
            ->expects($this->once())
            ->method('getCache')
            ->withAnyParameters('cache_name') // @see MethodMetadata mock
            ->will($this->returnValue($cache))
        ;

        $operation = new CacheableOperation(
            $cacheManager,
            $keyGenerator,
            $this->getExpressionCompiler()
        );

        $actualResult = $operation->handle($this->getMethodMetadata(), $methodInvocation);

        $this->assertSame('cachedValue', $actualResult);
    }

    public function testCacheableOperationProperlySetValueToCacheIfItIsMissing()
    {
        $methodInvocation = $this->getMethodInvocation();
        $methodInvocation
            ->expects($this->once())
            ->method('proceed')
            ->will($this->returnValue('notCachedValue'))
        ;

        $keyGenerator = $this->getKeyGenerator();
        $keyGenerator
            ->expects($this->once())
            ->method('generateKey')
            ->withAnyParameters()
            ->will($this->returnValue('notCachedValue'))
        ;

        $cache = $this->getCache();
        $cache
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('notCachedValue'))
            ->will($this->returnValue(null))
        ;

        $cache
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo('notCachedValue'), $this->equalTo('notCachedValue'))
            ->will($this->returnValue(true))
        ;

        $cacheManager = $this->getCacheManager();
        $cacheManager
            ->expects($this->exactly(2))
            ->method('getCache')
            ->with($this->equalTo('cache_name')) // @see MethodMetadata mock
            ->will($this->returnValue($cache))
        ;

        $operation = new CacheableOperation(
            $cacheManager,
            $keyGenerator,
            $this->getExpressionCompiler()
        );

        $actualResult = $operation->handle($this->getMethodMetadata(), $methodInvocation);

        $this->assertSame('notCachedValue', $actualResult);
    }

    protected function getMethodMetadata()
    {
        $metadata = $this->getMockBuilder('Kitano\CacheBundle\Metadata\CacheableMethodMetadata')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $metadata->caches = array('cache_name');
        $metadata->key    = null; // @see KeyGenerator Mock, always returns foo

        return $metadata;
    }
}

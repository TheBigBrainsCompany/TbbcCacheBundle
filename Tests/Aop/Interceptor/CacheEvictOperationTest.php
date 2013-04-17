<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Tests\Aop\Interceptor;

use Kitano\CacheBundle\Aop\Interceptor\CacheEvictOperation;
use Kitano\CacheBundle\Aop\Interceptor\CacheableOperation;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
class CacheEvictOperationTest extends AbstractCacheOperationTest
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

        $operation = new CacheEvictOperation(
            $this->getCacheManager(),
            $this->getKeyGenerator(),
            $this->getExpressionCompiler()
        );

        $operation->handle($incorrectMethodMetadata, $this->getMethodInvocation());
    }

    public function testCacheEvictOperationProperlyFlushAllEntriesIfDefined()
    {
        $metadata = $this->getMethodMetadata();
        $metadata->allEntries = true;

        $methodInvocation = $this->getMethodInvocation();
        $methodInvocation
            ->expects($this->once())
            ->method('proceed')
            ->will($this->returnValue('evictedValue'))
        ;

        $keyGenerator = $this->getKeyGenerator();
        $keyGenerator
            ->expects($this->never())
            ->method('generateKey')
        ;

        $cache = $this->getCache();
        $cache
            ->expects($this->never())
            ->method('get')
        ;

        $cache
            ->expects($this->never())
            ->method('set')
        ;

        $cache
            ->expects($this->once())
            ->method('flush')
        ;

        $cacheManager = $this->getCacheManager();
        $cacheManager
            ->expects($this->once())
            ->method('getCache')
            ->withAnyParameters()
            ->will($this->returnValue($cache))
        ;

        $operation = new CacheEvictOperation(
            $cacheManager,
            $keyGenerator,
            $this->getExpressionCompiler()
        );

        $actualResult = $operation->handle($metadata, $methodInvocation);
        $this->assertEquals('evictedValue', $actualResult);
    }

    public function testCacheEvictOperationDoesNotFlushAllEntriesIfNotDefined()
    {
        $metadata = $this->getMethodMetadata();
        $metadata->allEntries = false;

        $methodInvocation = $this->getMethodInvocation();
        $methodInvocation
            ->expects($this->once())
            ->method('proceed')
            ->will($this->returnValue('evictedValue'))
        ;

        $keyGenerator = $this->getKeyGenerator();
        $keyGenerator
            ->expects($this->once())
            ->method('generateKey')
            ->withAnyParameters()
            ->will($this->returnValue('evictedValueKey'))
        ;

        $cache = $this->getCache();
        $cache
            ->expects($this->never())
            ->method('get')
        ;

        $cache
            ->expects($this->never())
            ->method('set')
        ;

        $cache
            ->expects($this->never())
            ->method('flush')
        ;

        $cache
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('evictedValueKey'))
            ->will($this->returnValue(true))
        ;

        $cacheManager = $this->getCacheManager();
        $cacheManager
            ->expects($this->once())
            ->method('getCache')
            ->withAnyParameters()
            ->will($this->returnValue($cache))
        ;

        $operation = new CacheEvictOperation(
            $cacheManager,
            $keyGenerator,
            $this->getExpressionCompiler()
        );

        $actualResult = $operation->handle($metadata, $methodInvocation);
        $this->assertEquals('evictedValue', $actualResult);
    }

    protected function getMethodMetadata()
    {
        $metadata = $this->getMockBuilder('Kitano\CacheBundle\Metadata\CacheEvictMethodMetadata')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $metadata->caches = array('cache_name');
        $metadata->key    = null; // @see KeyGenerator Mock, always returns foo

        return $metadata;
    }
}

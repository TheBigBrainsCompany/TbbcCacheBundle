<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Tests\Aop\Interceptor;

use Kitano\CacheBundle\Aop\Interceptor\CacheUpdateOperation;
use Kitano\CacheBundle\Aop\Interceptor\CacheableOperation;

/**
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class CacheUpdateOperationTest extends AbstractCacheOperationTest
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

        $operation = new CacheUpdateOperation($this->getCacheManager(), $this->getKeyGenerator());
        $operation->handle($incorrectMethodMetadata, $this->getMethodInvocation());
    }

    public function testCacheUpdateOperationProperlySetValueToCache()
    {
        $methodInvocation = $this->getMethodInvocation();
        $methodInvocation
            ->expects($this->once())
            ->method('proceed')
            ->will($this->returnValue('toBeCachedValue'))
        ;

        $keyGenerator = $this->getKeyGenerator();
        $keyGenerator
            ->expects($this->once())
            ->method('generateKey')
            ->withAnyParameters()
            ->will($this->returnValue('toBeCachedValue'))
        ;

        $cache = $this->getCache();
        $cache
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo('toBeCachedValue'), $this->equalTo('toBeCachedValue'))
            ->will($this->returnValue(true))
        ;

        $cacheManager = $this->getCacheManager();
        $cacheManager
            ->expects($this->once())
            ->method('getCache')
            ->with($this->equalTo('cache_name')) // @see MethodMetadata mock
            ->will($this->returnValue($cache))
        ;

        $operation = new CacheUpdateOperation($cacheManager, $keyGenerator);
        $actualResult = $operation->handle($this->getMethodMetadata(), $methodInvocation);

        $this->assertSame('toBeCachedValue', $actualResult);
    }

    protected function getMethodMetadata()
    {
        $metadata = $this->getMockBuilder('Kitano\CacheBundle\Metadata\CacheUpdateMethodMetadata')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $metadata->caches = array('cache_name');
        $metadata->key    = null; // @see KeyGenerator Mock, always returns foo

        return $metadata;
    }


}

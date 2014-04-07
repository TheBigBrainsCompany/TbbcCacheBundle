<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Tests\Aop\Interceptor;

use Tbbc\CacheBundle\Aop\Interceptor\CacheUpdateOperation;

/**
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class CacheUpdateOperationTest extends AbstractCacheOperationTest
{
    /**
     * @expectedException \Tbbc\CacheBundle\Exception\InvalidArgumentException
     */
    public function testHandleWithWrongMethodMetadataThrowsAnException()
    {
        $methodInvocation = $this->getMethodInvocation();
        $methodInvocation
            ->expects($this->never())
            ->method('proceed')
        ;

        $incorrectMethodMetadata = $this->getMockBuilder('Tbbc\CacheBundle\Metadata\CacheMethodMetadataInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $operation = new CacheUpdateOperation(
            $this->getCacheManager(),
            $this->getKeyGenerator(),
            $this->getExpressionLanguage(),
            $this->getEventDispatcher()
        );

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

        $operation = new CacheUpdateOperation(
            $cacheManager,
            $keyGenerator,
            $this->getExpressionLanguage(),
            $this->getEventDispatcher()
        );

        $actualResult = $operation->handle($this->getMethodMetadata(), $methodInvocation);

        $this->assertSame('toBeCachedValue', $actualResult);
    }

    protected function getMethodMetadata()
    {
        $metadata = $this->getMockBuilder('Tbbc\CacheBundle\Metadata\CacheUpdateMethodMetadata')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $metadata->caches = array('cache_name');
        $metadata->key    = null; // @see KeyGenerator Mock, always returns foo

        return $metadata;
    }

}

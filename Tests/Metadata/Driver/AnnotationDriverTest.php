<?php

namespace Kitano\CacheBundle\Tests\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Kitano\CacheBundle\Metadata\Driver\AnnotationDriver;
use Kitano\CacheBundle\Metadata\MethodMetadata;
use Pel\Expression\Expression;

class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadMetadataWithMethodCacheable()
    {
        $driver = new AnnotationDriver(new AnnotationReader());

        $metadata = $driver->loadMetadataForClass(new \ReflectionClass('Kitano\CacheBundle\Tests\Metadata\Driver\Fixtures\CacheableService'));

        $this->assertArrayHasKey('findFoo', $metadata->methodMetadata);

        $this->assertEquals(array('foo_cache'), $metadata->methodMetadata['findFoo']->caches);
        $this->assertEquals(MethodMetadata::CACHE_OPERATION_GET_OR_SET, $metadata->methodMetadata['findFoo']->cacheOperation);
    }

    public function testLoadMetadataWithMethodCacheEvict()
    {
        $driver = new AnnotationDriver(new AnnotationReader());

        $metadata = $driver->loadMetadataForClass(new \ReflectionClass('Kitano\CacheBundle\Tests\Metadata\Driver\Fixtures\CacheableService'));

        $this->assertArrayHasKey('saveFoo', $metadata->methodMetadata);

        $this->assertEquals(array('foo_cache'), $metadata->methodMetadata['saveFoo']->caches);
        $this->assertEquals(MethodMetadata::CACHE_OPERATION_EVICT, $metadata->methodMetadata['saveFoo']->cacheOperation);
        $this->assertEquals(new Expression('#foo'), $metadata->methodMetadata['saveFoo']->key);
    }

    public function testLoadMetadataWithMethodCacheEvictAndAllEntriesSetsToTrue()
    {
        $driver = new AnnotationDriver(new AnnotationReader());

        $metadata = $driver->loadMetadataForClass(new \ReflectionClass('Kitano\CacheBundle\Tests\Metadata\Driver\Fixtures\CacheableService'));

        $method = 'saveFooAndEvictAllEntries';

        $this->assertArrayHasKey('saveFooAndEvictAllEntries', $metadata->methodMetadata);
        $this->assertEquals(array('foo_cache'), $metadata->methodMetadata[$method]->caches);
        $this->assertEquals(MethodMetadata::CACHE_OPERATION_EVICT, $metadata->methodMetadata[$method]->cacheOperation);
        $this->assertTrue($metadata->methodMetadata[$method]->allEntries);
    }
}



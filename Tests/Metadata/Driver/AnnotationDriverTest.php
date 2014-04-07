<?php

namespace Tbbc\CacheBundle\Tests\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\ExpressionLanguage\Expression;
use Tbbc\CacheBundle\Metadata\Driver\AnnotationDriver;

class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadMetadataWithMethodCacheable()
    {
        $driver = new AnnotationDriver(new AnnotationReader());

        $metadata = $driver->loadMetadataForClass(new \ReflectionClass('Tbbc\CacheBundle\Tests\Metadata\Driver\Fixtures\CacheableService'));

        $this->assertArrayHasKey('findFoo', $metadata->methodMetadata);

        $this->assertEquals(array('foo_cache'), $metadata->methodMetadata['findFoo']->caches);
        $this->assertEquals('cacheable', $metadata->methodMetadata['findFoo']->getOperation());
    }

    public function testLoadMetadataWithMethodCacheEvict()
    {
        $driver = new AnnotationDriver(new AnnotationReader());

        $metadata = $driver->loadMetadataForClass(new \ReflectionClass('Tbbc\CacheBundle\Tests\Metadata\Driver\Fixtures\CacheableService'));

        $this->assertArrayHasKey('saveFoo', $metadata->methodMetadata);

        $this->assertEquals(array('foo_cache'), $metadata->methodMetadata['saveFoo']->caches);
        $this->assertEquals('cache_evict', $metadata->methodMetadata['saveFoo']->getOperation());
        $this->assertEquals(new Expression('#foo'), $metadata->methodMetadata['saveFoo']->key);
    }

    public function testLoadMetadataWithMethodCacheEvictAndAllEntriesSetsToTrue()
    {
        $driver = new AnnotationDriver(new AnnotationReader());

        $metadata = $driver->loadMetadataForClass(new \ReflectionClass('Tbbc\CacheBundle\Tests\Metadata\Driver\Fixtures\CacheableService'));

        $method = 'saveFooAndEvictAllEntries';

        $this->assertArrayHasKey('saveFooAndEvictAllEntries', $metadata->methodMetadata);
        $this->assertEquals(array('foo_cache'), $metadata->methodMetadata[$method]->caches);
        $this->assertEquals('cache_evict', $metadata->methodMetadata[$method]->getOperation());
        $this->assertTrue($metadata->methodMetadata[$method]->allEntries);
    }
}

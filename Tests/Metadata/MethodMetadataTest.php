<?php
/**
 * @author Boris Guéry <guery.b@gmail.com>
 */

namespace Kitano\CacheBundle\Tests\Metadata;


use Kitano\CacheBundle\Metadata\MethodMetadata;

class MethodMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testMethodMetadataSerializeAndUnserializeRequiredProperties()
    {
        $expectedData = array(
            'key'            => 'CacheKey',
            'caches'         => array('foo'),
            'cacheOperation' => 'evict',
            'allEntries'     => 'shouldBeABoolean',
        );
        $metadata = new MethodMetadata('Kitano\CacheBundle\Tests\Metadata\MethodMetadataStub', 'fooMethod');
        $metadata->key            = $expectedData['key'];
        $metadata->caches         = $expectedData['caches'];
        $metadata->cacheOperation = $expectedData['cacheOperation'];
        $metadata->allEntries     = $expectedData['allEntries'];

        $serializedMetadata   = serialize($metadata);
        $unserializedMetadata = unserialize($serializedMetadata);

        $this->assertEquals($expectedData['key'],            $unserializedMetadata->key);
        $this->assertEquals($expectedData['caches'],         $unserializedMetadata->caches);
        $this->assertEquals($expectedData['cacheOperation'], $unserializedMetadata->cacheOperation);
        $this->assertEquals($expectedData['allEntries'],     $unserializedMetadata->allEntries);
    }
}

class MethodMetadataStub
{
    public function fooMethod() {}
}

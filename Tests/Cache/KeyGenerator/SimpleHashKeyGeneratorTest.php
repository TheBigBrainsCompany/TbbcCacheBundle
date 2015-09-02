<?php

namespace Tbbc\CacheBundle\Tests\Cache\KeyGenerator;

use Tbbc\CacheBundle\Cache\KeyGenerator\SimpleHashKeyGenerator;

class SimpleHashKeyGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SimpleHashKeyGenerator
     */
    private $generator;

    public function setUp()
    {
        parent::setUp();

        $this->generator = new SimpleHashKeyGenerator();
    }

    public function testSingleScalarParameter()
    {
        $expected = sha1(1234 . sha1('foo'));

        $this->assertEquals($expected, $this->generator->generateKey('foo'));
    }

    public function testArrayOfScalarParameters()
    {
        $expected = sha1(1234 . sha1('foo') . sha1('bar'));

        $this->assertEquals($expected, $this->generator->generateKey(array('foo', 'bar')));
    }

    public function testNullParameter()
    {
        $expected = sha1(1234 . 5678);

        $this->assertEquals($expected, $this->generator->generateKey(null));
    }

    public function testArrayParameter()
    {
        $parameter = array('foo', 'bar');
        $expected = sha1(1234 . sha1(serialize($parameter)));

        $this->assertEquals($expected, $this->generator->generateKey(array($parameter)));
    }

    public function testArrayOfMixedParameter()
    {
        $param1 = new Foo();
        $param2 = array('foo', 'bar');
        $param3 = 'foo';
        $param4 = null;

        $expected = sha1(1234 . sha1(serialize($param1)) . sha1(serialize($param2)) . sha1($param3) . 5678);

        $this->assertEquals($expected, $this->generator->generateKey(array(
            $param1,
            $param2,
            $param3,
            $param4
        )));
    }

    public function testBigArraysDontProduceSameKey()
    {
        $firstHash = $this->generator->generateKey(array('foo', 'bar', 'baz', 'unicorn'));
        $secondHash = $this->generator->generateKey(array('foo', 'bar', 'baz', 'poney'));

        $this->assertNotEquals($firstHash, $secondHash);
    }
}

class Foo
{
    public $bar = 'bar';
}

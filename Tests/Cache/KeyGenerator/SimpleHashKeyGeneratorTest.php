<?php

namespace Kitano\CacheBundle\Tests\Cache\KeyGenerator;

use Kitano\CacheBundle\Cache\KeyGenerator\SimpleHashKeyGenerator;

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
        $expected = base_convert(1234 . md5('foo'), 16, 10);

        $this->assertEquals($expected, $this->generator->generateKey('foo'));
    }

    public function testArrayOfScalarParameters()
    {
        $expected = base_convert(1234 . md5('foo') . md5('bar'), 16, 10);

        $this->assertEquals($expected, $this->generator->generateKey(array('foo', 'bar')));
    }

    public function testNullParameter()
    {
        $expected = base_convert(1234 . 5678, 16, 10);

        $this->assertEquals($expected, $this->generator->generateKey(null));
    }

    public function testArrayParameter()
    {
        $parameter = array('foo', 'bar');
        $expected = base_convert(1234 . md5(serialize($parameter)), 16, 10);

        $this->assertEquals($expected, $this->generator->generateKey(array($parameter)));
    }

    public function testArrayOfMixedParameter()
    {
        $param1 = new Foo();
        $param2 = array('foo', 'bar');
        $param3 = 'foo';
        $param4 = null;

        $expected = base_convert(1234 . md5(serialize($param1)) . md5(serialize($param2)) . md5($param3) . 5678, 16, 10);

        $this->assertEquals($expected, $this->generator->generateKey(array(
            $param1,
            $param2,
            $param3,
            $param4
        )));
    }
}

class Foo
{
    public $bar = 'bar';
}
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
        $expected = md5(1234 + md5('foo'));

        $this->assertEquals($expected, $this->generator->generateKey('foo'));
    }

    public function testArrayOfScalarParameters()
    {
        $expected = md5(1234 + md5('foo') + md5('bar'));

        $this->assertEquals($expected, $this->generator->generateKey(array('foo', 'bar')));
    }

    public function testNullParameter()
    {
        $expected = md5(1234 + 5678);

        $this->assertEquals($expected, $this->generator->generateKey(null));
    }

    public function testArrayParameter()
    {
        $parameter = array('foo', 'bar');
        $expected = md5(1234 + md5(serialize($parameter)));

        $this->assertEquals($expected, $this->generator->generateKey(array($parameter)));
    }

    public function testArrayOfMixedParameter()
    {
        $param1 = new Foo();
        $param2 = array('foo', 'bar');
        $param3 = 'foo';
        $param4 = null;

        $expected = md5(1234 + md5(serialize($param1)) + md5(serialize($param2)) + md5($param3) + 5678);

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
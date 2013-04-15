<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bguery
 * Date: 15/04/13
 * Time: 23:12
 * To change this template use File | Settings | File Templates.
 */

namespace Kitano\CacheBundle\Tests\Cache\KeyGenerator;


use Kitano\CacheBundle\Cache\KeyGenerator\LiteralKeyGenerator;

class LiteralKeyGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LiteralKeyGenerator
     */
    private $generator;

    public function setUp()
    {
        parent::setUp();

        $this->generator = new LiteralKeyGenerator();
    }

    public function testSingleScalarParameter()
    {
        $expected = 'foo_' . sha1(serialize(array('foo')));

        $this->assertEquals($expected, $this->generator->generateKey('foo'));
    }

    public function testArrayOfScalarParameters()
    {
        $expected = 'foo_bar_' . sha1(serialize(array('foo', 'bar')));

        $this->assertEquals($expected, $this->generator->generateKey(array('foo', 'bar')));
    }
}

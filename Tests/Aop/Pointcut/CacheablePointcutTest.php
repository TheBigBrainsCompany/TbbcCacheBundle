<?php


namespace Kitano\CacheBundle\Tests\Aop\Pointcut;


use Kitano\CacheBundle\Aop\Pointcut\CacheablePointcut;

class CacheablePointcutTest extends \PHPUnit_Framework_TestCase
{
    private $metadataFactory;

    public function testMatchesClassReturnTrueWhenEligibleClass()
    {
        $pointcut = new CacheablePointcut($this->metadataFactory);
        $pointcut->setEligibleClasses(array('stdClass'));

        $this->assertTrue($pointcut->matchesClass(new \ReflectionClass('stdClass')));
    }

    public function testMatchesClassReturnFalseWhenEligibleClass()
    {
        $pointcut = new CacheablePointcut($this->metadataFactory);
        $pointcut->setEligibleClasses(array());

        $this->assertFalse($pointcut->matchesClass(new \ReflectionClass('stdClass')));
    }

    protected function setUp()
    {
        $this->metadataFactory = $this->getMock('Metadata\MetadataFactoryInterface');
    }
}

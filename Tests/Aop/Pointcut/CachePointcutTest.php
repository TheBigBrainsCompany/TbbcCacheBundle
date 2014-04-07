<?php

namespace Tbbc\CacheBundle\Tests\Aop\Pointcut;

use Tbbc\CacheBundle\Aop\Pointcut\CachePointcut;

class CachePointcutTest extends \PHPUnit_Framework_TestCase
{
    private $metadataFactory;

    public function testMatchesClassReturnTrueWhenEligibleClass()
    {
        $pointcut = new CachePointcut($this->metadataFactory);
        $pointcut->setEligibleClasses(array('stdClass'));

        $this->assertTrue($pointcut->matchesClass(new \ReflectionClass('stdClass')));
    }

    public function testMatchesClassReturnFalseWhenEligibleClass()
    {
        $pointcut = new CachePointcut($this->metadataFactory);
        $pointcut->setEligibleClasses(array());

        $this->assertFalse($pointcut->matchesClass(new \ReflectionClass('stdClass')));
    }

    protected function setUp()
    {
        $this->metadataFactory = $this->getMock('Metadata\MetadataFactoryInterface');
    }
}

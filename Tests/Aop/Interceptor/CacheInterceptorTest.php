<?php

namespace Tbbc\CacheBundle\Tests\Aop\Interceptor;

use CG\Proxy\MethodInvocation;
use Tbbc\CacheBundle\Aop\Interceptor\CacheInterceptor;
use Tbbc\CacheBundle\Metadata\CacheableMethodMetadata;
use Tbbc\CacheBundle\Metadata\ClassMetadata;
use Metadata\MetadataFactoryInterface;

class CacheInterceptorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testInvokeThrowsExceptionWhenNoCachesAreSet()
    {
        list($interceptor,,) = $this->getInterceptor();

        $this->getInvocation($interceptor)->proceed();
    }

    protected function getInvocation(CacheInterceptor $interceptor, $method = 'findSomething', $arguments = array())
    {
        if ('findSomething' === $method && 0 === count($arguments)) {
            $arguments = array(new \stdClass(), new \stdClass());
        }
        $object = new CacheableService();

        return new MethodInvocation(new \ReflectionMethod($object, $method), $object, $arguments, array($interceptor));
    }

    protected function getInterceptor(MetadataFactoryInterface $metadataFactory = null)
    {
        if (null === $metadataFactory) {
            $metadataFactory = $this->getMock('Metadata\MetadataFactoryInterface');

            $metadata = new ClassMetadata('Tbbc\CacheBundle\Tests\Aop\Interceptor\CacheableService');
            $metadata->methodMetadata['findSomething'] = new CacheableMethodMetadata('Tbbc\CacheBundle\Tests\Aop\Interceptor\CacheableService', 'findSomething');

            $metadataFactory
                ->expects($this->once())
                ->method('getMetadataForClass')
                ->with($this->equalTo('Tbbc\CacheBundle\Tests\Aop\Interceptor\CacheableService'))
                ->will($this->returnValue($metadata))
            ;
        }

        $cacheManager       = $this->getMock('Tbbc\CacheBundle\Cache\CacheManagerInterface');
        $keyGenerator       = $this->getMock('Tbbc\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface');
        $expressionLanguage = $this->getMock('\Symfony\Component\ExpressionLanguage\ExpressionLanguage');
        $eventDispatcher    = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');

        return array(
            new CacheInterceptor($metadataFactory, $cacheManager, $keyGenerator, $expressionLanguage, $eventDispatcher),
            $cacheManager,
            $keyGenerator,
        );
    }
}

class CacheableService
{
    public function findSomething($foo)
    {
        return $foo;
    }
}

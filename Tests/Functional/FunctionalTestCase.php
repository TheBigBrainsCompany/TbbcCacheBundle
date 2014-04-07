<?php

namespace Tbbc\CacheBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class FunctionalTestCase extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $client = static::createClient();
    }


    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return static::$kernel->getContainer();
    }
}

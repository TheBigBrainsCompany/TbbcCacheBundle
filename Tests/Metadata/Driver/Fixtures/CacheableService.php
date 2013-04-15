<?php

namespace Kitano\CacheBundle\Tests\Metadata\Driver\Fixtures;

use Kitano\CacheBundle\Annotation\Cacheable;
use Kitano\CacheBundle\Annotation\CacheEvict;

class CacheableService
{
    /**
     * @Cacheable(caches="foo_cache")
     */
    public function findFoo($bar, $baz)
    {
        $foo = 'foo';

        return $foo;
    }

    /**
     * @CacheEvict(caches="foo_cache", key="#foo")
     */
    public function saveFoo($foo)
    {
    }

    /**
     * @CacheEvict(caches="foo_cache", allEntries=true)
     */
    public function saveFooAndEvictAllEntries($foo)
    {
    }
}

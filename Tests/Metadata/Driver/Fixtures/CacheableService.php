<?php

namespace Tbbc\CacheBundle\Tests\Metadata\Driver\Fixtures;

use Tbbc\CacheBundle\Annotation\Cacheable;
use Tbbc\CacheBundle\Annotation\CacheEvict;

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

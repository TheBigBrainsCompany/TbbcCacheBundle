<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Cache;

use Tbbc\CacheBundle\Exception\InvalidArgumentException;

/**
 * Abstract Class CacheManager
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
abstract class CacheManager implements CacheManagerInterface
{
    /**
     * @var array of string
     */
    protected $cacheNames;

    /**
     * @var array|CacheInterface[]
     */
    protected $cacheMap;

    /**
     * Adds a Cache
     *
     * @param CacheInterface $cache
     */
    public function addCache(CacheInterface $cache)
    {
        $this->cacheMap[$cache->getName()] = $cache;
        $this->cacheNames[]                = $cache->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getCache($name)
    {
        if (!$this->hasCache($name)) {
            throw new InvalidArgumentException(sprintf('No cache with the name "%s" registered.', $name));
        }

        return $this->cacheMap[$name];
    }

    /**
     * Returns whether or not a cache with the given name is registered.
     *
     * @param  string $name
     * @return bool
     */
    public function hasCache($name)
    {
        return isset($this->cacheMap[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheNames()
    {
        return $this->cacheNames;
    }
}

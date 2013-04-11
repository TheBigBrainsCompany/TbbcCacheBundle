<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Cache;

/**
 * Interface Cache
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
interface CacheInterface
{
    /**
     * Returns the cache name
     *
     * @return string
     */
    public function getName();

    /**
     * Return the value to which this cache maps the specified key.
     *
     * @param string $key The unique key of the cache entry to fetch.
     * @return mixed The cached data or null, if no cache entry exists for the given id.
     */
    public function get($key);

    /**
     * Puts data into the cache.
     *
     * @param string $key
     * @param mixed  $value
     * @return boolean TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    public function set($key, $value);

    /**
     * Deletes the mapping for this key from this cache
     *
     * @param string $key
     * @return boolean TRUE if the cache entry was successfully deleted, FALSE otherwise.
     */
    public function delete($key);

    /**
     * Invalidates all mappings from the cache
     *
     * @return mixed
     */
    public function flush();
}
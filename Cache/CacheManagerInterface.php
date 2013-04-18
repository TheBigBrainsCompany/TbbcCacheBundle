<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Cache;

/**
 * Interface CacheManager
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
interface CacheManagerInterface
{
    /**
     * Returns the Cache associated with the given name.
     *
     * @param  string         $name
     * @return CacheInterface
     */
    public function getCache($name);

    /**
     * Returns a list of registered cache names.
     *
     * @return array of string
     */
    public function getCacheNames();
}

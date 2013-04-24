<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle;

/**
 * Contains all events dispatched by the KitanoCacheBundle
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
final class CacheEvents
{
    /**
     * The AFTER_CACHE_HIT event occurs after a cache hit on key fetch.
     *
     * This event gives the developer a chance to modify the fetched value
     * before it is returned.
     *
     * @var string
     */
    const AFTER_CACHE_HIT = 'kitano_cache.after_cache_hit';

    /**
     * The AFTER_CACHE_UPDATE event occurs after a cache entry has been updated.
     *
     * @var string
     */
    const AFTER_CACHE_UPDATE = 'kitano_cache.after_cache_update';
}

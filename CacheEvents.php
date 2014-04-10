<?php

/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle;

/**
 * Contains all events dispatched by the TbbcCacheBundle
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
    const AFTER_CACHE_HIT = 'tbbc_cache.after_cache_hit';

    /**
     * The AFTER_CACHE_UPDATE event occurs after a cache entry has been updated.
     *
     * @var string
     */
    const AFTER_CACHE_UPDATE = 'tbbc_cache.after_cache_update';
}

<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Cache\KeyGenerator;

/**
 * Class KeyGeneratorInterface
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
interface KeyGeneratorInterface
{
    /**
     * @param  mixed  $params An array of mixed values used for generating the key
     * @return string Unique key
     */
    public function generateKey($params);
}

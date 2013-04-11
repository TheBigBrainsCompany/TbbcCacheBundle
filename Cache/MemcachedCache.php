<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Cache;

use \Doctrine\Common\Cache\MemcachedCache as DoctrineMemcachedCache;

/**
 * Class MemcachedCache
 * For now, this is a proxy for Doctrine\Common\Cache\MemcachedCache
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class MemcachedCache implements CacheInterface
{
    protected $name;
    protected $doctrineMemcachedCache;

    public function __construct($name, DoctrineMemcachedCache $doctrineMemcachedCache)
    {
        $this->name = $name;
        $this->doctrineMemcachedCache = $doctrineMemcachedCache;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->doctrineMemcachedCache->fetch($key);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        return $this->doctrineMemcachedCache->save($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return $this->doctrineMemcachedCache->delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        return $this->doctrineMemcachedCache->flushAll();
    }


    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }
}
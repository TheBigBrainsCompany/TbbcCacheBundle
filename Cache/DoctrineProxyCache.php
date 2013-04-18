<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Cache;

use \Doctrine\Common\Cache\Cache as DoctrineCache;

/**
 * Class MemcachedCache
 * For now, this is a proxy for Doctrine\Common\Cache\MemcachedCache
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class DoctrineProxyCache implements CacheInterface
{
    protected $name;
    protected $doctrineCache;
    protected $ttl;

    public function __construct($name, DoctrineCache $doctrineCache, $ttl = null)
    {
        $this->name          = $name;
        $this->doctrineCache = $doctrineCache;
        $this->doctrineCache->setNamespace($name);
        $this->ttl = $ttl;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->doctrineCache->fetch($key);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        return $this->doctrineCache->save($key, $value, (int) $this->ttl);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return $this->doctrineCache->delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        return $this->doctrineCache->flushAll();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }
}

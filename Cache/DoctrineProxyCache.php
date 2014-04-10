<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Cache;

use Doctrine\Common\Cache\CacheProvider;

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

    public function __construct($name, CacheProvider $doctrineCacheProvider, $ttl = null)
    {
        $this->name          = $name;
        $this->doctrineCache = $doctrineCacheProvider;
        $this->doctrineCache->setNamespace($name);
        $this->ttl = $ttl;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if (false === ($value = $this->doctrineCache->fetch($key))) {
            return null;
        }

        return $value;
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

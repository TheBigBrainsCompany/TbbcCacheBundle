<?php

/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Event;

use Metadata\MethodMetadata;

/**
 * Class CacheUpdateEvent
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class CacheUpdateEvent extends CacheEvent
{
    /**
     * Value returned by cache
     *
     * @var mixed
     */
    protected $value;

    /**
     * Cache name
     *
     * @var string
     */
    protected $cache;

    public function __construct(MethodMetadata $metadata, $cache, $key, $value)
    {
        parent::__construct($metadata, $key);
        $this->value = $value;
        $this->cache = $cache;
    }


    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCache()
    {
        return $this->cache;
    }
}

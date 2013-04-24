<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Event;

use Metadata\MethodMetadata;

/**
 * Class CacheHitEvent
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class CacheHitEvent extends CacheEvent
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

    public function __construct(MethodMetadata $metadata, $cache, $key, &$value)
    {
        parent::__construct($metadata, $key);
        $this->value = &$value;
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
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCache()
    {
        return $this->cache;
    }
}

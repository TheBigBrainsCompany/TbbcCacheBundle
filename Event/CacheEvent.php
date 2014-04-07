<?php

/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Event;

use Metadata\MethodMetadata;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CacheEvent
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
abstract class CacheEvent extends Event
{
    /**
     * @var MethodMetadata
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $key;

    public function __construct(MethodMetadata $metadata, $key)
    {
        $this->metadata = $metadata;
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return \Metadata\MethodMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
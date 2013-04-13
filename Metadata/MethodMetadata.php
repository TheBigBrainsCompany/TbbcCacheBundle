<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Metadata;

use Metadata\MethodMetadata as BaseMethodMetadata;

/**
 * Contains method metadata information
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class MethodMetadata extends BaseMethodMetadata
{
    const CACHE_OPERATION_GET_OR_SET = 'get_or_set';
    const CACHE_OPERATION_EVICT = 'evict';

    public $cacheOperation;
    public $caches = array();
    public $key;

    public function serialize()
    {
        return serialize(array(
            parent::serialize(),
            $this->caches, $this->key
        ));
    }

    public function unserialize($str)
    {
        list($parentStr,
            $this->caches, $this->key
        ) = unserialize($str);

        parent::unserialize($parentStr);
    }
}

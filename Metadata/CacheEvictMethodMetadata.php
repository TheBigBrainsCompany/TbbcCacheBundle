<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Metadata;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
class CacheEvictMethodMetadata extends AbstractCacheMethodMetadata
{
    public $allEntries;

    public function serialize()
    {
        return serialize(array(
            parent::serialize(),
            $this->allEntries
        ));
    }

    public function unserialize($str)
    {
        list($parentStr,
            $this->allEntries
        ) = unserialize($str);

        parent::unserialize($parentStr);
    }

    public function getOperation()
    {
        return 'cache_evict';
    }
}

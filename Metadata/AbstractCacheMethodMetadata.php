<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Metadata;

use Metadata\MethodMetadata;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
abstract class AbstractCacheMethodMetadata extends MethodMetadata implements CacheMethodMetadataInterface
{
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

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
    public $caches = array();

    public function serialize()
    {
        return serialize(array(
            parent::serialize(),
            $this->caches,
        ));
    }

    public function unserialize($str)
    {
        list($parentStr,
            $this->caches
        ) = unserialize($str);

        parent::unserialize($parentStr);
    }
}

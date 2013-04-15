<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Metadata;

/**
 * Contains method metadata information
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 * @author Boris Guéry <guery.b@gmail.com>
 */
class CacheableMethodMetadata extends AbstractCacheMethodMetadata
{
    public function getOperation()
    {
        return 'cacheable';
    }
}

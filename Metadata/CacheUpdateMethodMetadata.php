<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Metadata;

/**
 * Contains method metadata information
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class CacheUpdateMethodMetadata extends AbstractCacheMethodMetadata
{
    public function getOperation()
    {
        return 'cache_update';
    }
}

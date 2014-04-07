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
 * @author Boris Guéry <guery.b@gmail.com>
 */
class CacheableMethodMetadata extends AbstractCacheMethodMetadata
{
    public function getOperation()
    {
        return 'cacheable';
    }
}

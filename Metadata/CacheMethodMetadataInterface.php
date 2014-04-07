<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Metadata;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
interface CacheMethodMetadataInterface
{
    /**
     * Returns the associated operation name
     *
     * @return string
     */
    public function getOperation();
}

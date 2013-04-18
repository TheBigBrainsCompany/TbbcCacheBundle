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
interface CacheMethodMetadataInterface
{
    /**
     * Returns the associated operation name
     *
     * @return string
     */
    public function getOperation();
}

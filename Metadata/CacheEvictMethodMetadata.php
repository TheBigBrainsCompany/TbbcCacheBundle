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

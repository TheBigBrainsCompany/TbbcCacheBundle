<?php

/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Annotation;

use Tbbc\CacheBundle\Exception\InvalidArgumentException;

/**
 * Base class for Cache annotations.
 *
 * @Annotation
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
abstract class Cache
{
    /**
     * @Required
     * @var mixed
     */
    public $caches;

    /**
     * @Required
     * @var string
     */
    public $key;

    public function __construct(array $values)
    {
        if (!isset($values['caches'])) {
            throw new InvalidArgumentException('You must define a "caches" attribute for each Cacheable annotation.');
        }

        $this->caches = array_map('trim', explode(',', $values['caches']));

        if (isset($values['key'])) {
            $this->key = $values['key'];
        }
    }
}

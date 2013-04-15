<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Annotation;

/**
 * Represents a @CacheEvict annotation.
 *
 * @Annotation
 * @Target("METHOD")
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
final class CacheEvict extends Cache
{
    public $allEntries = false;

    public function __construct(array $values)
    {
        parent::__construct($values);

        if (isset($values['allEntries'])) {
            $this->allEntries = 'true' == $values['allEntries'] ? true : false;
        }
    }
}

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
}

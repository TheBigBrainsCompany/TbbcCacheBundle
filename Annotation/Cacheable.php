<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Annotation;

/**
 * Represents a @Cacheable annotation.
 *
 * @Annotation
 * @Target("METHOD")
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
final class Cacheable extends Cache
{
}

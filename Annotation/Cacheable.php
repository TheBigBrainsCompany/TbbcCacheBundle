<?php

/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Annotation;

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

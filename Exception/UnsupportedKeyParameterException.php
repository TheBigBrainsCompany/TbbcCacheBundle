<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Exception;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
class UnsupportedKeyParameterException extends InvalidArgumentException
{
    public function __construct($param)
    {
        parent::__construct(sprintf('Only scalar values are allowed to be used as key, "%s" provided', gettype($param)));
    }
}

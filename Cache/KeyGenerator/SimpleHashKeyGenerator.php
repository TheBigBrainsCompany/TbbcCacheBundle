<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Cache\KeyGenerator;

use Kitano\CacheBundle\Exception\UnsupportedKeyParameterException;

/**
 * Class SimpleHashKeyGenerator
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class SimpleHashKeyGenerator implements KeyGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function generateKey($params)
    {
        $parameters = !is_array($params) ? array($params) : $params;

        $hash = 1234;
        foreach($parameters as $parameter) {
            if (!is_scalar($parameter)) {

                throw new UnsupportedKeyParameterException($parameter);
            }
            $hash = $hash + ((null == $parameter) ? 5678 : md5($parameter));
        }

        return md5($hash);
    }
}

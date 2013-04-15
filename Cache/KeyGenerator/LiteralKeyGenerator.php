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
 * Class LiteralKeyGenerator
 *
 * @author Boris Guéry <guery.b@gmail.com>
 */
class LiteralKeyGenerator implements KeyGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function generateKey($params)
    {
        $parameters = !is_array($params) ? array($params) : $params;

        $key = '';
        foreach($parameters as $parameter) {
            if (!is_scalar($parameter)) {

                throw new UnsupportedKeyParameterException($parameter);
            }
            $key .= preg_replace('/[^a-zA-Z0-9\s]/', '-', $parameter);
        }
        $uniqueHash = sha1(serialize($parameters));

        return $key . '_' . $uniqueHash;
    }
}

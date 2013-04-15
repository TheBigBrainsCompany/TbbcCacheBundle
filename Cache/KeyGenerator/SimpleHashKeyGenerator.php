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
            if (null == $parameter) {
                $paramHash = 5678;
            } elseif (is_scalar($parameter)) {
                $paramHash = md5($parameter);
            } elseif (is_array($parameter) || is_object($parameter)) {
                $paramHash = md5(serialize($parameter));
            } else {
                throw new UnsupportedKeyParameterException(sprintf('Not supported parameter type "%s"',
                    gettype($parameter)));
            }

            $hash = $hash + $paramHash;
        }

        return md5($hash);
    }
}

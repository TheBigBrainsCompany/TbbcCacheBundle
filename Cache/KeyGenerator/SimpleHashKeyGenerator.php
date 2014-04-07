<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Cache\KeyGenerator;

use Tbbc\CacheBundle\Exception\UnsupportedKeyParameterException;

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
        foreach ($parameters as $parameter) {
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

            $hash = $hash . $paramHash;
        }

        return base_convert($hash, 16, 10);
    }
}

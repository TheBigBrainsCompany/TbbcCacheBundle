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
        foreach ($parameters as $parameter) {
            if (!is_scalar($parameter)) {

                throw new UnsupportedKeyParameterException($parameter);
            }
            $key .= preg_replace('/[^a-zA-Z0-9\s]/', '-', $parameter) . '_';
        }

        $uniqueHash = sha1(serialize($parameters));

        return $key . $uniqueHash;
    }
}

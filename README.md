CacheBundle
===========

State : Unstable.


Configuration
-------------

```YAML
services:
    my_manager.product:
        class: My\Manager\ProductManager
        tags:
            - { name: kitano_cache.cache_eligible }

kitano_cache:
    annotations: { enabled: true }
    manager: simple_cache
    cache:
        products:
            type: memcached
            servers:
                memcached-01: { host: localhost, port: 11211 }
```

*Note*: The `kitano_cache.cache_eligible` tag is mandatory in your service definition if you want to be able to use
 annotation for this service.

Usage
-----

```PHP
<?php

namespace My\Manager;

use Kitano\CacheBundle\Annotation\Cacheable;

class ProductManager
{
    // ...


    /**
     * @Cacheable(caches="products")
     */
    public function getProduct($sku)
    {
        $product = $this->productRepository->findProductBySku($sku);
        // ...

        return $product;
    }

    // OR manually

    public function getProduct($sku, $isPublished)
    {
        $cacheKey = $this->cacheKeyGenerator->generate(array($sku, $isPublished));
        $cache = $this->cacheManager->getCache('products');
        if ($product = $cache->get($cacheKey)) {
            return $product;
        }

        $product = $this->productRepository->findProductBySku($sku);
        // ...

        $cache->set($cacheKey, $product);

        return $product;
    }

    // ...
}
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
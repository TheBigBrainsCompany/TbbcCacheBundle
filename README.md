CacheBundle
===========

State : Unstable.


Configuration
-------------

```YAML
kitano_cache:
    manager: simple_cache
    cache:
        products:
            type: memcached
            servers:
                memcached-01: { host: localhost, port: 11211}
```

Usage
-----

```PHP
<?php

namespace My\BookManager;

// ....

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

    public function getProduct($sku)
    {
        $cacheKey = $this->cacheKeyGenerator->generate($sku);
        $cache = $this->cacheManager->getCache('products');
        if ($product = $cache->get($cacheKey)) {
            return $product;
        }

        $product = $this->productRepository->findProductBySku($sku);
        // ...

        $cache->set($cacheKey, $product);

        return $product;
    }
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
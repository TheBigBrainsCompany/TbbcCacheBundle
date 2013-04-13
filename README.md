CacheBundle
===========

Add cache abstraction and method annotations for controlling cache.
The current implementation of the Cache component is a wrapper (proxy) for Doctrine\Common\Cache.


State
-----

Unstable.

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
    public function getProduct($sku, $isPublished)
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
}
```

### Using expressions (created after the PEL: [PHP Expression Language](https://github.com/Kitano/php-expression)):

```PHP
<?php

namespace My\Manager;

use My\Model\Product;

use Kitano\CacheBundle\Annotation\Cacheable;
use Kitano\CacheBundle\Annotation\CacheEvict;

class ProductManager
{
    //...

    /**
     * @Cacheable(caches="products", key="#sku")
     */
    public function getProduct($sku, $type = 'book')
    {
        $product = new Product($sku, $type);

        return $product;
    }

    /**
     * @CacheEvict(caches="products", key="#product.getSku()")
     */
    public function saveProduct(Product $product)
    {
        // saving product ...
    }
}
```

```PHP
<?php

namespace My\Model;

class Product
{
    private $sku;
    private $type;

    public function __construct($sku, $type)
    {
        $this->sku = $sku;
        $this->type = $type;
    }


    public function getSku()
    {
        return $this->sku;
    }

    public function getType()
    {
        return $this->type;
    }
}
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

# CacheBundle

Add cache abstraction and method annotations for controlling cache.
The current implementation of the Cache component is a wrapper (proxy) for Doctrine\Common\Cache.

## TODO

* Add TTL strategy
* Add annotation handling for overridden classes
* Write more tests
* Use cache name (from config) as namespace for cache keys
* Add cache configuration factories for all available cache drivers in Doctrine Cache
* Add @CacheUpdate annotation for updating cache entry after method execution
* Add options to @CacheEvict annotation for deleting all entries from cache.

## Index

* [State](#state)
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
    * [Basic usage](#basic-usage)
    * [Custom Cache Manager](#custom-cache-manager)
    * [Key generation](#key-generation)
    * [Custom Key generation](#custom-key-generation)
    * [Annotation based caching (recommanded)](#annotation-based-caching)
        * [@Cacheable annotation](#cacheable-annotation)
        * [@CacheEvict annotation](#cacheevict-annotation)
        * [Expression Language](#expression-language)
* [Testing](#testing)
* [License](#license)


## State

Unstable. [![Build Status](https://travis-ci.org/Kitano/KitanoCacheBundle.png?branch=master)](https://travis-ci.org/Kitano/KitanoCacheBundle)


## Installation

```bash
$ php composer.phar require kitano/cache-bundle
```

## Configuration

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

## Usage

### Basic Usage

`CacheManager` instance must be injected into services that need cache management.

The `CacheManager` gives access to each configured cache (see [Configuration](#configuration) section).
Each cache implements [CacheInterface](https://github.com/Kitano/KitanoCacheBundle/tree/master/Cache/CacheInterface.php).

Usage:

```PHP
<?php

namespace My\Manager;

use Kitano\CacheBundle\Annotation\Cacheable;

class ProductManager
{
    private $cacheManager;
    private $keyGenerator;

    public function __construct(CacheManagerInterface $cacheManager, KeyGeneratorInterface $keyGenerator)
    {
        $this->cacheManager = $cacheManager;
        $this->keyGenerator = $keyGenerator;
    }


    public function getProduct($sku, $type = 'book')
    {
        $cacheKey = $this->keyGenerator->generate($sku);
        $cache = $this->cacheManager->getCache('products');
        if ($product = $cache->get($cacheKey)) {
            return $product;
        }

        $product = $this->productRepository->findProductBySkuAndType($sku, $type);
        // ...

        $cache->set($cacheKey, $product);

        return $product;
    }

    public function saveProduct(Product $product)
    {
        // saving product ...

        $cacheKey = $this->keyGenerator->generate($product->getSku());
        $this->cacheManager->getCache('products')->delete($cacheKey);
    }
}
```

### Custom Cache Manager

Out of the box, the bundle provides a
[SimpleCacheManager](https://github.com/Kitano/KitanoCacheBundle/tree/master/Cache/SimpleCacheManager.php), but
custom cache managers can be used instead of the default one and must implement the
[CacheManagerInterface](https://github.com/Kitano/KitanoCacheBundle/tree/master/Cache/CacheManagerInterface.php).


### Key generation

Key generation is up to the developer, but for convenience, the bundle comes with some key generation logic.

**Note**: When using [Annotation based caching](#annotation-based-caching), usage of Key generators is mandatory.

Out of the box, the bundle provides a
[SimpleHashKeyGenerator](https://github.com/Kitano/KitanoCacheBundle/tree/master/Cache/KeyGenerator/SimpleHashKeyGenerator.php)
which basically adds each param encoded using md5 algorithm, and returned a md5 hash of the result.

### Custom Key generation

Custom key generators can be used instead of the default one and must implement the
[KeyGeneratorInterface](https://github.com/Kitano/KitanoCacheBundle/tree/master/Cache/KeyGenerator/KeyGeneratorInterface.php).


### Annotation based caching

**Recommended**

If some prefer to avoid repeating code each time they want to add some caching logic, the bundle can automate the process
by using [AOP](http://en.wikipedia.org/wiki/Aspect-oriented_programming) approach and annotations.

The bundle provides the following annotations:
* [@Cacheable](#@cacheable-annotation)
* [@CacheEvict](#@cacheevict-annotation)

#### @Cacheable annotation

@Cacheable annotation is used to automatically store the result of a method into the cache.

When a method demarcated with the @Cacheable annotation is called, the bundle checks if an entry exists in the cache
before executing the method. If it finds one, the cache result is returned without having to actually execute the method.

If no cache entry is found, the method is executed and the bundle automatically stores its result into the cache.

```PHP
<?php

namespace My\Manager;

use My\Model\Product;

use Kitano\CacheBundle\Annotation\Cacheable;

class ProductManager
{
    /**
     * @Cacheable(caches="products", key="#sku")
     */
    public function getProduct($sku, $type = 'book')
    {
        $product = new Product($sku, $type);

        return $product;
    }
}
```

#### @CacheEvict annotation

@CacheEvict annotation allows methods to trigger cache population or cache eviction.

When a method is demarcated with @CacheEvict annotation, the bundle will execute the method and then will automatically
try to delete the cache entry with the provided key.

```PHP
<?php

namespace My\Manager;

use My\Model\Product;

use Kitano\CacheBundle\Annotation\CacheEvict;

class ProductManager
{
    /**
     * @CacheEvict(caches="products", key="#product.getSku()")
     */
    public function saveProduct(Product $product)
    {
        // saving product ...
    }
}
```

#### Expression Language

For key generation, [PHP Expression Language](https://github.com/Kitano/php-expression) can be used.

**TODO**: write some doc here


## Testing

Install development dependencies

```bash
$ composer install --dev
```

Run the test suite

```bash
$ vendor/bin/phpunit
```

## License

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

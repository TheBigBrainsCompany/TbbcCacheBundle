<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Metadata\Driver;

use Kitano\CacheBundle\Annotation\CacheEvict;
use Kitano\CacheBundle\Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;
use Doctrine\Common\Annotations\Reader;
use Pel\Expression\Expression;
use \ReflectionClass;
use \ReflectionMethod;
use Kitano\CacheBundle\Annotation\Cacheable;
use Kitano\CacheBundle\Metadata\MethodMetadata;

/**
 * Loads cache annotations and converts them to metadata
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class AnnotationDriver implements DriverInterface
{
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function loadMetadataForClass(ReflectionClass $reflection)
    {
        $classMetadata = new ClassMetadata($reflection->getName());

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED) as $method) {
            // check if the method was defined on this class (vs abstract)
            if ($method->getDeclaringClass()->getName() !== $reflection->getName()) {
                continue;
            }

            $annotations = $this->reader->getMethodAnnotations($method);
            if (!$annotations) {
                continue;
            }

            if (null !== $methodMetadata = $this->convertMethodAnnotations($method, $annotations)) {
                $classMetadata->addMethodMetadata($methodMetadata);
            }
        }

        return $classMetadata;
    }

    private function convertMethodAnnotations(\ReflectionMethod $method, array $annotations)
    {
        $parameters = array();
        foreach ($method->getParameters() as $index => $parameter) {
            $parameters[$parameter->getName()] = $index;
        }

        $methodMetadata = new MethodMetadata($method->class, $method->name);
        $hasCacheMetadata = false;
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Cacheable || $annotation instanceof CacheEvict) {
                $methodMetadata->caches = $annotation->caches;
                if (!empty($annotation->key)) {
                    $methodMetadata->key = new Expression($annotation->key);
                }

                $methodMetadata->cacheOperation = ($annotation instanceof Cacheable) ?
                    MethodMetadata::CACHE_OPERATION_GET_OR_SET
                  : MethodMetadata::CACHE_OPERATION_EVICT;

                $hasCacheMetadata = true;
            }

            if ($annotation instanceof CacheEvict) {
                $methodMetadata->allEntries = $annotation->allEntries;
            }
        }

        return $hasCacheMetadata ? $methodMetadata : null;
    }
}

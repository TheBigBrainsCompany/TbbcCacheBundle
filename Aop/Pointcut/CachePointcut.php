<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Aop\Pointcut;

use CG\Core\ClassUtils;
use Metadata\MetadataFactoryInterface;
use JMS\AopBundle\Aop\PointcutInterface;

/**
 * Class CachePointcut
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class CachePointcut implements PointcutInterface
{
    private $metadataFactory;
    private $eligibleClasses = array();

    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function matchesMethod(\ReflectionMethod $method)
    {
        $userClass = ClassUtils::getUserClass($method->class);
        $metadata = $this->metadataFactory->getMetadataForClass($userClass);

        if (null === $metadata) {
            return false;
        }

        return isset($metadata->methodMetadata[$method->name]);
    }

    /**
     * {@inheritDoc}
     */
    public function matchesClass(\ReflectionClass $class)
    {
        foreach ($this->eligibleClasses as $eligibleClass) {
            if ($class->name === $eligibleClass || $class->isSubclassOf($eligibleClass)) {
                return true;
            }
        }

        return false;
    }

    public function setEligibleClasses(array $eligibleClasses)
    {
        $this->eligibleClasses = $eligibleClasses;
    }
}

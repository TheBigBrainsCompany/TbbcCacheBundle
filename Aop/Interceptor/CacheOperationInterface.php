<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\Aop\Interceptor;

use CG\Proxy\MethodInvocation;
use Kitano\CacheBundle\Metadata\CacheMethodMetadataInterface;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
interface CacheOperationInterface
{
    public function handle(CacheMethodMetadataInterface $methodMetadata, MethodInvocation $methodInvocation);
}

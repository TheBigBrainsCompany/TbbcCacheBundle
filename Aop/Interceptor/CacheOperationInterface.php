<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\Aop\Interceptor;

use CG\Proxy\MethodInvocation;
use Tbbc\CacheBundle\Metadata\CacheMethodMetadataInterface;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
interface CacheOperationInterface
{
    public function handle(CacheMethodMetadataInterface $methodMetadata, MethodInvocation $methodInvocation);
}

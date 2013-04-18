<?php
/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle;

use Kitano\CacheBundle\DependencyInjection\Compiler\CacheEligibleServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KitanoCacheBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $cacheEligibleServicesPass = new CacheEligibleServicesPass();
        $container->addCompilerPass($cacheEligibleServicesPass);
    }
}

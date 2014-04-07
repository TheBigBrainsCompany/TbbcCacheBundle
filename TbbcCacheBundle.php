<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle;

use Tbbc\CacheBundle\DependencyInjection\Compiler\CacheEligibleServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TbbcCacheBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $cacheEligibleServicesPass = new CacheEligibleServicesPass();
        $container->addCompilerPass($cacheEligibleServicesPass);
    }
}

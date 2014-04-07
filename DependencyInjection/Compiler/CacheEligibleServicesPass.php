<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Collects classes eligible to cache annotations.
 * These are user defined by using the "tbbc_cache.cache_eligible" Tag.
 * Classes without this Tag won't match and "Cache" family annotations won't
 * work for them.
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class CacheEligibleServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $eligibleClasses = array();
        foreach ($container->findTaggedServiceIds('tbbc_cache.cache_eligible') as $id => $attr) {
            $eligibleClasses[] = $container->getDefinition($id)->getClass();
        }

        $container
            ->getDefinition('tbbc_cache.aop.pointcut.cache')
            ->addMethodCall('setEligibleClasses', array($eligibleClasses))
        ;
    }
}

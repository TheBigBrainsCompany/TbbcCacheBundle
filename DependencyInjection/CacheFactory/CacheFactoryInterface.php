<?php
/**
 * This file is part of TbbcCacheBundle
 *
 * (c) TheBigBrainsCompany <contact@thebigbrainscompany.com>
 *
 */

namespace Tbbc\CacheBundle\DependencyInjection\CacheFactory;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface for the cache factories
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
interface CacheFactoryInterface
{
    /**
     * Creates the cache, registers it and returns its id
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     * @param string           $id        The id of the service
     * @param array            $config    An array of configuration
     */
    public function create(ContainerBuilder $container, $id, array $config);

    /**
     * Returns the key for the factory configuration
     *
     * @return string
     */
    public function getKey();

    /**
     * Adds configuration nodes for the factory
     *
     * @param NodeBuilder $builder
     */
    public function addConfiguration(NodeBuilder $builder);
}

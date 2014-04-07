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
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Factory for APC Cache
 *
 * @author Boris Guéry <guery.b@gmail.com>
 */
class ApcCacheFactory implements CacheFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {

        $doctrineApcId = sprintf('tbbc_cache.doctrine_cache.%s_apc_instance', $config['name']);
        $container
            ->setDefinition($doctrineApcId, new DefinitionDecorator('tbbc_cache.doctrine_cache.apc'))
            ->setPublic(false)
        ;

        $container
            ->setDefinition($id, new DefinitionDecorator('tbbc_cache.cache.doctrine_proxy'))
            ->addArgument($config['name'])
            ->addArgument(new Reference($doctrineApcId))
            ->addArgument($config['ttl'])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'apc';
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('ttl')->defaultNull()->end()
        ;
    }
}

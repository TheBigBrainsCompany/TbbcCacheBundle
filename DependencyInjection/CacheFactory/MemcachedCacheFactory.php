<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\DependencyInjection\CacheFactory;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Factory for Memcached Cache
 *
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class MemcachedCacheFactory implements CacheFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $memcached = new Definition('Memcached');
        foreach($config['servers'] as $server) {
            $memcached->addMethodCall('addServer', array(
                $server['host'],
                $server['port'],
            ));
        }
        $memcached->setPublic(false);
        $memcachedId = sprintf('kitano_cache.%s_memcached_instance', $config['name']);
        $container->setDefinition($memcachedId, $memcached);

        $doctrineMemcachedId = sprintf('kitano_cache.doctrine_cache.%s_memcached_instance', $config['name']);
        $container
            ->setDefinition($doctrineMemcachedId, new DefinitionDecorator('kitano_cache.doctrine_cache.memcached'))
            ->addMethodCall('setMemcached', array(new Reference($memcachedId)))
            ->setPublic(false)
        ;

        $container
            ->setDefinition($id, new DefinitionDecorator('kitano_cache.cache.doctrine_proxy'))
            ->addArgument($config['name'])
            ->addArgument(new Reference($doctrineMemcachedId))
            ->addArgument($config['ttl'])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'memcached';
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('ttl')->defaultNull()->end()
            ->arrayNode('servers')
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('port')->defaultValue(11211)->end()
                    ->end()
                ->end()
                ->defaultValue(array(
                    'localhost' => array(
                        'host' => 'localhost',
                        'port' => 11211,
                    )
                ))
            ->end()
        ;
    }
}
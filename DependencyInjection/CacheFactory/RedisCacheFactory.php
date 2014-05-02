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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Factory for Redis Cache
 *
 * @author Armen Mkrtchyan <tankist@gmail.com>
 */
class RedisCacheFactory implements CacheFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {

        $redis = new Definition('Redis');
        $redis->addMethodCall('connect', array(
            $config['server']['host'],
            $config['server']['port']
        ));

        $redis->setPublic(false);
        $redisId = sprintf('tbbc_cache.%s_redis_instance', $config['name']);
        $container->setDefinition($redisId, $redis);

        $doctrineRedisId = sprintf('tbbc_cache.doctrine_cache.%s_redis_instance', $config['name']);
        $container
            ->setDefinition($doctrineRedisId, new DefinitionDecorator('tbbc_cache.doctrine_cache.redis'))
            ->addMethodCall('setRedis', array(new Reference($redisId)))
            ->setPublic(false);

        $container
            ->setDefinition($id, new DefinitionDecorator('tbbc_cache.cache.doctrine_proxy'))
            ->addArgument($config['name'])
            ->addArgument(new Reference($doctrineRedisId))
            ->addArgument($config['ttl']);
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'redis';
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(NodeBuilder $node)
    {
        $node
            ->scalarNode('ttl')->defaultNull()->end()
            ->arrayNode('server')
            ->children()
                ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('port')->defaultValue(6379)->end()
            ->end()
            ->end();
    }
}

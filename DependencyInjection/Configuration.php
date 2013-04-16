<?php

/**
 * This file is part of KitanoCacheBundle
 *
 * (c) Kitano <contact@kitanolabs.org>
 *
 */

namespace Kitano\CacheBundle\DependencyInjection;

use Kitano\CacheBundle\DependencyInjection\CacheFactory\CacheFactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    private $cacheFactories;

    /**
     * Constructor
     *
     * @param array|CacheFactoryInterface[] $cacheFactories
     */
    public function __construct(array $cacheFactories)
    {
        $this->cacheFactories = $cacheFactories;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kitano_cache');

        $rootNode
            ->children()
                ->scalarNode('key_generator')
            ->end()
        ;

        $this->addAnnotationsSection($rootNode);
        $this->addManagerSection($rootNode);
        $this->addCacheSection($rootNode, $this->cacheFactories);
        $this->addMetadataSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Parses the kitano_cache.manager config section
     * Example for yaml driver:
     * kitano_cache:
     *     annotations:
     *         enabled: true
     *
     * @param  ArrayNodeDefinition $node
     * @return void
     */
    private function addAnnotationsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('annotations')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enabled')->defaultFalse()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Parses the kitano_cache.manager config section
     * Example for yaml driver:
     * kitano_cache:
     *     manager:
     *
     * @param  ArrayNodeDefinition $node
     * @return void
     */
    private function addManagerSection(ArrayNodeDefinition $node)
    {
        $supportedManagers = array('simple_cache');

        $node
            ->children()
                ->scalarNode('manager')
                    ->defaultValue('simple_cache')
                    ->validate()
                        ->ifNotInArray($supportedManagers)
                        ->thenInvalid('The cache manager "%s" is not supported. Please choose one of '.json_encode($supportedManagers))
                    ->end()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;
    }

    /**
     * Parses the kitano_cache.manager config section
     * Example for yaml driver:
     * kitano_cache:
     *     cache:
     *         type: memcached
     *         servers: ....
     *
     * @param ArrayNodeDefinition           $node
     * @param array|CacheFactoryInterface[] $cacheFactories
     */
    private function addCacheSection(ArrayNodeDefinition $node, array $cacheFactories)
    {
        $cacheNodeBuilder = $node
            ->children()
                ->arrayNode('cache')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')->isRequired()->cannotBeEmpty()->end()
        ;

        foreach ($cacheFactories as $factory) {
            $factory->addConfiguration($cacheNodeBuilder);
        }
    }

    /**
     * Parses the kitano_cache.metadata config section
     * Example for yaml driver:
     * kitano_cache:
     *     metadata:
     *         use_cache: true
     *         cache_dir: %kernel.cache_dir%/kitano_cache
     *
     * @param ArrayNodeDefinition $node
     */
    private function addMetadataSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('metadata')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('use_cache')->defaultTrue()->end()
                        ->scalarNode('cache_dir')->cannotBeEmpty()->defaultValue('%kernel.cache_dir%/kitano_cache')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}

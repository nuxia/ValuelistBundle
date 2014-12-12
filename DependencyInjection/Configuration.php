<?php

namespace Nuxia\ValuelistBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nuxia_valuelist');
        //@formatter:off
        $rootNode
            ->children()
                ->arrayNode('admin')
                    ->isRequired()
                    ->canBeDisabled()
                        ->children()
                            ->arrayNode('categories')
                                ->isRequired()
                                ->requiresAtLeastOneElement()
                                ->prototype('array')->treatNullLike(array('new', 'edit', 'delete'))
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        //@formatter:on

        return $treeBuilder;
    }
}

<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symandy_makefile_maker');

        $treeBuilder->getRootNode()
            ->fixXmlConfig('executable')
            ->fixXmlConfig('group')
            ->children()
                ->arrayNode('executables')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->isRequired()->end()
                            ->scalarNode('filename')->defaultNull()->end()
                            ->scalarNode('output')->defaultNull()->end()
                            ->scalarNode('path')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('groups')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->fixXmlConfig('command')
                        ->children()
                            ->arrayNode('commands')
                                ->useAttributeAsKey('name')
                                ->arrayPrototype()
                                    ->fixXmlConfig('instruction')
                                    ->children()
                                        ->scalarNode('description')->isRequired()->end()
                                        ->arrayNode('instructions')
                                            ->arrayPrototype()
                                                ->fixXmlConfig('option')
                                                ->fixXmlConfig('argument')
                                                ->children()
                                                    ->scalarNode('executable')->end()
                                                    ->scalarNode('name')->end()
                                                    ->arrayNode('arguments')
                                                        ->scalarPrototype()->end()
                                                    ->end()
                                                    ->arrayNode('options')
                                                        ->arrayPrototype()
                                                            ->children()
                                                                ->scalarNode('key')->isRequired()->end()
                                                                ->scalarNode('value')->defaultNull()->end()
                                                            ->end()
                                                        ->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

}

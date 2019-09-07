<?php
namespace CodeMade\LiquidBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('liquid');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('liquid');
        }

        $rootNode
            ->children()
                ->scalarNode('cache')->defaultValue('%kernel.cache_dir%/liquid')->end()
                /*->scalarNode('charset')->defaultValue('%kernel.charset%')->end()
                ->booleanNode('debug')->defaultValue('%kernel.debug%')->end()*/
                ->scalarNode('filter')->defaultFalse()->end()
                ->scalarNode('include_suffix')->defaultValue('liquid')->end()
                ->scalarNode('include_prefix')->defaultValue('')->end()
                ->scalarNode('default_path')
                    ->info('The default path used to load templates')
                    ->defaultValue('%kernel.project_dir%/templates')
                ->end()
                ->arrayNode('tags')
                ->normalizeKeys(false)
                ->useAttributeAsKey('tags')
                ->beforeNormalization()
                ->always()
                ->then(function ($tags) {
                    $normalized = [];
                    foreach ($tags as $name => $namespace) {
                        if (\is_array($namespace)) {
                            // xml
                            $name = $namespace['value'];
                            $namespace = $namespace['namespace'];
                        }
                        // path within the default namespace
                        if (ctype_digit((string) $namespace)) {
                            $name = $namespace;
                            $namespace = null;
                        }
                        $normalized[$name] = $namespace;
                    }
                    return $normalized;
                })
                ->end()
                ->prototype('variable')->end()
                ->end()
                ->arrayNode('paths')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('paths')
                    ->beforeNormalization()
                    ->always()
                    ->then(function ($paths) {
                        $normalized = [];
                        foreach ($paths as $namespace => $path) {
                            if (\is_array($namespace)) {
                                // xml
                                $path = $namespace['value'];
                                $namespace = $namespace['namespace'];
                            }
                            // path within the default namespace
                            if (ctype_digit((string) $path)) {
                                $path = $namespace;
                                $namespace = null;
                            }
                            $normalized[$namespace] = $path;
                        }
                        return $normalized;
                    })
                    ->end()
                    ->prototype('variable')->end()
                ->end()
        ;
        return $treeBuilder;
    }

}
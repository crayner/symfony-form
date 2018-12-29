<?php
namespace Hillrange\Form\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('hillrange_form');
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('upload_path')->end()
                ->scalarNode('button_class_off')->end()
                ->scalarNode('button_class_on')->end()
                ->scalarNode('use_font_awesome')->end()
                ->scalarNode('translation_domain')->end()
            ;

        return $treeBuilder;
    }
}
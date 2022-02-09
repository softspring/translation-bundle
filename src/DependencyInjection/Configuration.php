<?php

namespace Softspring\TranslationBundle\DependencyInjection;

use Softspring\TranslationBundle\Entity\Translation;
use Softspring\TranslationBundle\Entity\TranslationMessage;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sfs_translation');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('entity_manager')
                    ->defaultValue('default')
                ->end()

                ->scalarNode('translation_class')
                    ->defaultValue(Translation::class)
                ->end()

                ->scalarNode('translation_message_class')
                    ->defaultValue(TranslationMessage::class)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

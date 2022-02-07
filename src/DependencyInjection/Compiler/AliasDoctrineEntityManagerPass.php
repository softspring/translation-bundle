<?php

namespace Softspring\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AliasDoctrineEntityManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $emName = $container->getParameter('sfs_translation.entity_manager_name');

        $container->addAliases([
            'sfs_translation.entity_manager' => 'doctrine.orm.'.$emName.'_entity_manager'
        ]);
    }
}
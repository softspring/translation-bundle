<?php

namespace Softspring\TranslationBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Softspring\TranslationBundle\DependencyInjection\Compiler\AliasDoctrineEntityManagerPass;
use Softspring\TranslationBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfsTranslationBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $basePath = realpath(__DIR__.'/../config/doctrine-mapping/');

        $this->addRegisterMappingsPass($container, [$basePath.'/model' => 'Softspring\TranslationBundle\Model']);
        $this->addRegisterMappingsPass($container, [$basePath.'/entities' => 'Softspring\TranslationBundle\Entity']);

        $container->addCompilerPass(new AliasDoctrineEntityManagerPass());
        $container->addCompilerPass(new ResolveDoctrineTargetEntityPass());
    }

    /**
     * @param string|bool $enablingParameter
     */
    private function addRegisterMappingsPass(ContainerBuilder $container, array $mappings, $enablingParameter = false)
    {
        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['sfs_translation.entity_manager_name'], $enablingParameter));
    }
}

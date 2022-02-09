<?php

namespace Softspring\TranslationBundle\DependencyInjection\Compiler;

use Softspring\CoreBundle\DependencyInjection\Compiler\AbstractResolveDoctrineTargetEntityPass;
use Softspring\TranslationBundle\Model\TranslationInterface;
use Softspring\TranslationBundle\Model\TranslationMessageInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveDoctrineTargetEntityPass extends AbstractResolveDoctrineTargetEntityPass
{
    /**
     * {@inheritDoc}
     */
    protected function getEntityManagerName(ContainerBuilder $container): string
    {
        return $container->getParameter('sfs_translation.entity_manager_name');
    }

    public function process(ContainerBuilder $container)
    {
        $this->setTargetEntityFromParameter('sfs_translation.translation.class', TranslationInterface::class, $container, false);
        $this->setTargetEntityFromParameter('sfs_translation.translation_message.class', TranslationMessageInterface::class, $container, false);
    }
}

<?php

namespace Softspring\TranslationBundle\DependencyInjection;

use Softspring\TranslationBundle\Entity\Translation;
use Softspring\TranslationBundle\Entity\TranslationMessage;
use Softspring\TranslationBundle\Model\TranslationInterface;
use Softspring\TranslationBundle\Model\TranslationMessageInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SfsTranslationExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config/services'));

        $container->setParameter('sfs_translation.entity_manager_name', $config['entity_manager']);

//        $container->setParameter('sfs_translation.templates', $config['templates'] ?? []);
//
//        $container->setParameter('sfs_translation.from_email.sender_name', $config['from_email']['sender_name'] ?? null);
//        $container->setParameter('sfs_translation.from_email.address', $config['from_email']['address'] ?? null);
        $container->setParameter('sfs_translation.admin', true);

        $loader->load('services.yaml');

        if ($container->getParameter('sfs_translation.admin')) {
            $loader->load('controller/admin_translations.yaml');
        }
    }

    public function prepend(ContainerBuilder $container)
    {
        $doctrineConfig = [];

        // add a default config to force load target_entities, will be overwritten by ResolveDoctrineTargetEntityPass
        $doctrineConfig['orm']['resolve_target_entities'][TranslationInterface::class] = Translation::class;
        $doctrineConfig['orm']['resolve_target_entities'][TranslationMessageInterface::class] = TranslationMessage::class;

        // disable auto-mapping for this bundle to prevent mapping errors
        $doctrineConfig['orm']['mappings']['SfsTranslationBundle'] = [
            'is_bundle' => true,
            'mapping' => false,
        ];

        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }
}
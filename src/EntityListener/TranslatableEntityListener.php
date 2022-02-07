<?php

namespace Softspring\TranslationBundle\EntityListener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;
use Softspring\TranslationBundle\Extractor\ObjectTranslationExtractor;
use Softspring\TranslationBundle\Manager\TranslationManagerInterface;
use Softspring\TranslationBundle\Manager\TranslationMessageManagerInterface;
use Softspring\TranslationBundle\Utils\Domain;

class TranslatableEntityListener implements EventSubscriberInterface
{
    protected TranslationManagerInterface $translationManager;

    protected TranslationMessageManagerInterface $translationMessageManager;

    protected ObjectTranslationExtractor $objectTranslationExtractor;

    /**
     * @param TranslationManagerInterface        $translationManager
     * @param TranslationMessageManagerInterface $translationMessageManager
     * @param ObjectTranslationExtractor         $objectTranslationExtractor
     */
    public function __construct(TranslationManagerInterface $translationManager, TranslationMessageManagerInterface $translationMessageManager, ObjectTranslationExtractor $objectTranslationExtractor)
    {
        $this->translationManager = $translationManager;
        $this->translationMessageManager = $translationMessageManager;
        $this->objectTranslationExtractor = $objectTranslationExtractor;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->processTranslations($args->getObject(), $args->getObjectManager());
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->processTranslations($args->getObject(), $args->getObjectManager());
    }

    protected function processTranslations(object $entity, ObjectManager $entityManager)
    {
        if (!$this->objectTranslationExtractor->isTranslatable($entity)) {
            return;
        }

        $translations = $this->objectTranslationExtractor->extractTranslations($entity, false);

        foreach ($translations as $translationKey => $defaultTranslation) {
            $locale = 'es';
            $domain = Domain::createFromClass($entity);
            $this->translationManager->saveTranslation($translationKey, $domain, $locale, $defaultTranslation, true, false);
        }

        $this->translationManager->getEntityManager()->flush();
    }


}
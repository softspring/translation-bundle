<?php

namespace Softspring\TranslationBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerTrait;
use Softspring\TranslationBundle\Model\TranslationInterface;
use Softspring\TranslationBundle\Model\TranslationMessageInterface;

class TranslationManager implements TranslationManagerInterface
{
    use CrudlEntityManagerTrait;

    protected EntityManagerInterface $em;

    /**
     * @var TranslationMessageManagerInterface
     */
    protected $messageManager;

    public function __construct(EntityManagerInterface $em, TranslationMessageManagerInterface $messageManager)
    {
        $this->em = $em;
        $this->messageManager = $messageManager;
    }

    public function getTargetClass(): string
    {
        return TranslationInterface::class;
    }

    public function saveTranslation(string $key, string $domain, string $locale, string $text, bool $isDefaultLocale = true, bool $flush = true)
    {
        $translation = $this->getRepository()->findOneBy(['key' => $key, 'domain' => $domain]);

        if (!$translation) {
            $translation = $this->createEntity();
            $translation->setKey($key);
        }

        $translation->setDomain($domain);
        $translationMessage = $translation->getTranslationMessageForLocale($locale);

        if (!$translationMessage) {
            $translationMessage = $this->messageManager->createEntity();
        }

        $translationMessage->setLocale($locale);
        $translationMessage->setMessage($text);
        $isDefaultLocale && $translation->setDefaultMessage($translationMessage);
        $translation->addTranslationMessage($translationMessage);

        $this->em->persist($translation);
        $flush && $this->em->flush();
    }

    public function getTranslation(string $key, string $domain, string $locale): ?TranslationMessageInterface
    {
        /** @var TranslationInterface $translation */
        $translation = $this->getRepository()->findOneBy(['key' => $key, 'domain' => $domain]);

        if (!$translation) {
            return null;
        }

        return $translation->getTranslationMessageForLocale($locale) ?: $translation->getDefaultMessage();
    }
}

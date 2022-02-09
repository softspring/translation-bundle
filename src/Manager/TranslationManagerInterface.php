<?php

namespace Softspring\TranslationBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\CrudlBundle\Manager\CrudlEntityManagerInterface;
use Softspring\TranslationBundle\Model\TranslationInterface;
use Softspring\TranslationBundle\Model\TranslationMessageInterface;

interface TranslationManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return TranslationInterface
     */
    public function createEntity();

    /**
     * @param TranslationInterface $entity
     */
    public function saveEntity($entity): void;

    /**
     * @param TranslationInterface $entity
     */
    public function deleteEntity($entity): void;

    public function saveTranslation(string $key, string $domain, string $locale, string $text, bool $isDefaultLocale = true, bool $flush = true);

    public function getTranslation(string $key, string $domain, string $locale): ?TranslationMessageInterface;

    public function getEntityManager(): EntityManagerInterface;
}

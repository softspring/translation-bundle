<?php

namespace Softspring\TranslationBundle\Model;

use Doctrine\Common\Collections\Collection;

interface TranslationInterface
{
    public function getKey(): ?string;

    public function setKey(?string $key): void;

    public function getDomain(): ?string;

    public function setDomain(?string $domain): void;

    public function getDefaultMessage(): ?TranslationMessageInterface;

    public function setDefaultMessage(?TranslationMessageInterface $defaultMessage): void;

    /**
     * @return Collection|TranslationMessageInterface[]
     */
    public function getTranslationMessages();

    public function addTranslationMessage(TranslationMessageInterface $translationMessage): void;

    public function removeTranslationMessage(TranslationMessageInterface $translationMessage): void;

    public function getTranslationMessageForLocale(string $locale): ?TranslationMessageInterface;
}

<?php

namespace Softspring\TranslationBundle\Model;

use Doctrine\Common\Collections\Collection;

interface TranslationInterface
{
    /**
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * @param string|null $key
     */
    public function setKey(?string $key): void;

    /**
     * @return string|null
     */
    public function getDomain(): ?string;

    /**
     * @param string|null $domain
     */
    public function setDomain(?string $domain): void;

    /**
     * @return TranslationMessageInterface|null
     */
    public function getDefaultMessage(): ?TranslationMessageInterface;

    /**
     * @param TranslationMessageInterface|null $defaultMessage
     */
    public function setDefaultMessage(?TranslationMessageInterface $defaultMessage): void;

    /**
     * @return Collection|TranslationMessageInterface[]
     */
    public function getTranslationMessages();

    /**
     * @param TranslationMessageInterface $translationMessage
     */
    public function addTranslationMessage(TranslationMessageInterface $translationMessage): void;

    /**
     * @param TranslationMessageInterface $translationMessage
     */
    public function removeTranslationMessage(TranslationMessageInterface $translationMessage): void;

    /**
     * @param string $locale
     *
     * @return TranslationMessageInterface|null
     */
    public function getTranslationMessageForLocale(string $locale): ?TranslationMessageInterface;
}
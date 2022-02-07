<?php

namespace Softspring\TranslationBundle\Model;

interface TranslationMessageInterface
{

    /**
     * @return TranslationInterface
     */
    public function getTranslation(): TranslationInterface;

    /**
     * @param TranslationInterface $translation
     */
    public function setTranslation(TranslationInterface $translation): void;

    /**
     * @return string|null
     */
    public function getLocale(): ?string;

    /**
     * @param string|null $locale
     */
    public function setLocale(?string $locale): void;

    /**
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void;
}
<?php

namespace Softspring\TranslationBundle\Model;

interface TranslationMessageInterface
{
    public function getTranslation(): TranslationInterface;

    public function setTranslation(TranslationInterface $translation): void;

    public function getLocale(): ?string;

    public function setLocale(?string $locale): void;

    public function getMessage(): ?string;

    public function setMessage(?string $message): void;
}

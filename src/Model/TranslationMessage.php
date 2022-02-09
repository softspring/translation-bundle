<?php

namespace Softspring\TranslationBundle\Model;

class TranslationMessage implements TranslationMessageInterface
{
    protected TranslationInterface $translation;

    protected ?string $locale;

    protected ?string $message;

    public function getTranslation(): TranslationInterface
    {
        return $this->translation;
    }

    public function setTranslation(TranslationInterface $translation): void
    {
        $this->translation = $translation;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}

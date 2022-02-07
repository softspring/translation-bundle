<?php

namespace Softspring\TranslationBundle\Model;

class TranslationMessage implements TranslationMessageInterface
{
    protected TranslationInterface $translation;

    protected ?string $locale;

    protected ?string $message;

    /**
     * @return TranslationInterface
     */
    public function getTranslation(): TranslationInterface
    {
        return $this->translation;
    }

    /**
     * @param TranslationInterface $translation
     */
    public function setTranslation(TranslationInterface $translation): void
    {
        $this->translation = $translation;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param string|null $locale
     */
    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
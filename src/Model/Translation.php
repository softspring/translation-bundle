<?php

namespace Softspring\TranslationBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Translation implements TranslationInterface
{
    protected ?string $key;

    protected ?string $domain;

    protected ?TranslationMessageInterface $defaultMessage;

    /**
     * @var Collection|TranslationMessageInterface[]
     */
    protected Collection $translationMessages;

    public function __construct()
    {
        $this->translationMessages = new ArrayCollection();
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): void
    {
        $this->domain = $domain;
    }

    public function getDefaultMessage(): ?TranslationMessageInterface
    {
        return $this->defaultMessage;
    }

    public function setDefaultMessage(?TranslationMessageInterface $defaultMessage): void
    {
        $this->defaultMessage = $defaultMessage;
    }

    /**
     * @return Collection|TranslationMessageInterface[]
     */
    public function getTranslationMessages()
    {
        return $this->translationMessages;
    }

    public function addTranslationMessage(TranslationMessageInterface $translationMessage): void
    {
        if (!$this->translationMessages->contains($translationMessage)) {
            $this->translationMessages->add($translationMessage);
            $translationMessage->setTranslation($this);
        }
    }

    public function removeTranslationMessage(TranslationMessageInterface $translationMessage): void
    {
        if ($this->translationMessages->contains($translationMessage)) {
            $this->translationMessages->removeElement($translationMessage);
            $translationMessage->setTranslation(null);
        }
    }

    public function getTranslationMessageForLocale(string $locale): ?TranslationMessageInterface
    {
        if (!$this->translationMessages) {
            $this->translationMessages = new ArrayCollection();
        }

        return $this->translationMessages->filter(function (TranslationMessageInterface $translationMessage) use ($locale) {
            return $translationMessage->getLocale() === $locale;
        })->first() ?: null;
    }
}

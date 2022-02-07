<?php

namespace Softspring\TranslationBundle\Extractor;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Softspring\TranslationBundle\Configuration\Translatable;
use Softspring\TranslationBundle\Configuration\Translation;
use Softspring\TranslationBundle\Configuration\TranslationsEmbed;
use Softspring\TranslationBundle\Configuration\TranslationsHtml;
use Softspring\TranslationBundle\Manager\TranslationManagerInterface;
use Softspring\TranslationBundle\Model\TranslationInterface;
use Softspring\TranslationBundle\Model\TranslationMessageInterface;
use Softspring\TranslationBundle\Utils\Domain;
use Softspring\TranslationBundle\Utils\Html;
use Softspring\TranslationBundle\Utils\Keys;
use Symfony\Component\DomCrawler\Crawler;

class ObjectTranslationExtractor
{
    protected TranslationManagerInterface $translationManager;
    protected AnnotationReader $annotationReader;

    /**
     * @param TranslationManagerInterface $translationManager
     */
    public function __construct(TranslationManagerInterface $translationManager)
    {
        $this->translationManager = $translationManager;
        $this->annotationReader = new AnnotationReader();
    }

    public function getObjectTranslations(object $translatable): Collection
    {
        $translationKeys = $this->getTranslationKeys($translatable);

        $translations = $this->translationManager->getRepository()->findBy([]);

        if (is_array($translations)) {
            $translations = new ArrayCollection($translations);
        }

        return $translations->filter(function (TranslationInterface $translation) use ($translationKeys) {
            return in_array($translation->getKey(), $translationKeys);
        });
    }

    public function getTranslationKeys(object $translatable): array
    {
        return array_keys($this->extractTranslations($translatable));
    }

    public function extractTranslations(object $entity, bool $recursive = true)
    {
        if (!$this->isTranslatable($entity)) {
            return [];
        }

        $reflectionClass = new \ReflectionClass($entity);

        $translations = [];

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ($annotation = $this->annotationReader->getPropertyAnnotation($reflectionProperty, Translation::class)) {
                $translations += $this->_extractDirectTranslations($annotation, $entity, $reflectionProperty, $recursive);
            }

            if ($embeddedAnnotation = $this->annotationReader->getPropertyAnnotation($reflectionProperty, TranslationsEmbed::class)) {
                $translations += $this->_extractEmbedTranslations($embeddedAnnotation, $entity, $reflectionProperty);
            }

            if ($htmlAnnotation = $this->annotationReader->getPropertyAnnotation($reflectionProperty, TranslationsHtml::class)) {
                $translations += $this->_extractHtmlTranslations($htmlAnnotation, $entity, $reflectionProperty);
            }
        }

        return $translations;
    }

    public function _extractDirectTranslations(Translation $annotation, object $entity, \ReflectionProperty $reflectionProperty, bool $recursive = true): array
    {
        $translations = [];

        $value = $reflectionProperty->getDeclaringClass()->getMethod('get' . ucfirst($reflectionProperty->getName()))->invoke($entity);

        if ($value instanceof Collection) {
            if ($recursive) {
                foreach ($value as $valueItem) {
                    $translations += $this->extractTranslations($valueItem);
                }
            }
        } elseif (is_string($value)) {
            $translations[Keys::getTranslationKey($entity, $reflectionProperty->getName())] = $value;
        } elseif (is_object($value)) {
            $translations += $this->extractTranslations($value);
        }

        return $translations;
    }

    public function _extractEmbedTranslations(TranslationsEmbed $annotation, object $entity, \ReflectionProperty $reflectionProperty): array
    {
        $translations = [];

        $value = $reflectionProperty->getDeclaringClass()->getMethod('get' . ucfirst($reflectionProperty->getName()))->invoke($entity);

        $embeddedReflectionClass = new \ReflectionClass($value);

        foreach ($embeddedReflectionClass->getProperties() as $embeddedReflectionProperty) {
            if ($propertyAnnotation = $this->annotationReader->getPropertyAnnotation($embeddedReflectionProperty, Translation::class)) {
                $embeddedValue = $embeddedReflectionClass->getMethod('get' . ucfirst($embeddedReflectionProperty->getName()))->invoke($value);

                if (is_string($embeddedValue)) {
                    $translations[Keys::getTranslationEmbeddedKey($entity, $reflectionProperty->getName(), $embeddedReflectionProperty->getName())] = $embeddedValue;
                }
            }
        }

        return $translations;
    }

    public function _extractHtmlTranslations(TranslationsHtml $annotation, object $entity, \ReflectionProperty $reflectionProperty): array
    {
        $value = $reflectionProperty->getDeclaringClass()->getMethod('get' . ucfirst($reflectionProperty->getName()))->invoke($entity);

        return Html::getTranslations($value, $entity, $reflectionProperty->getName());
    }

    public function isTranslatable(object $entity): bool
    {
        if ($entity instanceof TranslationInterface) {
            return false;
        }

        if ($entity instanceof TranslationMessageInterface) {
            return false;
        }

        $annotation = $this->annotationReader->getClassAnnotation(new \ReflectionClass($entity), Translatable::class);

        return (bool) $annotation;
    }
}
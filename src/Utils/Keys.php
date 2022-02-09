<?php

namespace Softspring\TranslationBundle\Utils;

use Doctrine\Common\Annotations\AnnotationReader;
use Softspring\TranslationBundle\Configuration\Translatable;

class Keys
{
    public static function getObjectPrefix(object $object): string
    {
        $annotationReader = new AnnotationReader();

        $reflectionClass = new \ReflectionClass($object);

        if ($reflectionClass->hasProperty('__isInitialized__')) {
            $reflectionClass = new \ReflectionClass($reflectionClass->getParentClass()->getName());
        }

        $translatableAnnotation = $annotationReader->getClassAnnotation($reflectionClass, Translatable::class);

        if ($translatableAnnotation->prefix) {
            $prefix = $translatableAnnotation->prefix;
        } else {
            $prefix = Domain::toSnakeCase($reflectionClass->getShortName()).'_';
        }

        return $prefix.$object->getId();
    }

    public static function getTranslationKey(object $object, string $field): string
    {
        return self::getTranslationPart($object, $field);
    }

    public static function getTranslationPart(object $object, string $part): string
    {
        return self::getObjectPrefix($object).'.'.Domain::toSnakeCase($part);
    }

    public static function getTranslationEmbeddedKey(object $object, string $embeddedProperty, string $field): string
    {
        $prefix = self::getTranslationKey($object, $embeddedProperty);

        return $prefix.'.'.Domain::toSnakeCase($field);
    }
}

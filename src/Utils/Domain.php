<?php

namespace Softspring\TranslationBundle\Utils;

use Doctrine\Common\Annotations\AnnotationReader;

class Domain
{
    public static function createFromClassName(string $className): string
    {
        $reflectionClass = new \ReflectionClass($className);

        if ($reflectionClass->hasProperty('__isInitialized__')) {
            $className = $reflectionClass->getParentClass()->getName();
        }

        return self::toSnakeCase(str_replace(['/','\\'], '__', $className));
    }

    public static function createFromClass(object $object): string
    {
        return self::createFromClassName(get_class($object));
    }

    public static function toSnakeCase(string $string): string
    {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
    }
}
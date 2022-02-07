<?php

namespace Softspring\TranslationBundle\Configuration;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class TranslationsEmbed implements TranslationAnnotationInterface
{
    public $prefix = null;
}
<?php

namespace Softspring\TranslationBundle\Configuration;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Translation implements TranslationAnnotationInterface
{
    public $size = null;
}
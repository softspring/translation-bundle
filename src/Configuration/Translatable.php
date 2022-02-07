<?php

namespace Softspring\TranslationBundle\Configuration;

/**
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
class Translatable implements TranslationAnnotationInterface
{
    public $prefix = null;
}
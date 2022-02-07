<?php

namespace Softspring\TranslationBundle\Utils;

use Symfony\Component\DomCrawler\Crawler;

class Html
{
    public static function getTranslations(string $html, object $entity, string $field): array
    {
        $translations = [];

        $crawler = new Crawler($html);

        foreach ($crawler->filter('trans') as $trans) {
            $value = $trans->nodeValue;
            $id = $trans->getAttribute('id');
            $translations[Keys::getTranslationPart($entity, "$field.$id")] = $value;
        }

        return $translations;
    }
}
<?php

namespace Softspring\TranslationBundle\Twig\Extension;

use Softspring\TranslationBundle\Extractor\ObjectTranslationExtractor;
use Softspring\TranslationBundle\Manager\TranslationManagerInterface;
use Softspring\TranslationBundle\Manager\TranslationMessageManagerInterface;
use Softspring\TranslationBundle\Model\TranslationMessageInterface;
use Softspring\TranslationBundle\Utils\Domain;
use Softspring\TranslationBundle\Utils\Html;
use Softspring\TranslationBundle\Utils\Keys;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TranslationExtension extends AbstractExtension
{
    protected TranslationManagerInterface $translationManager;
    protected TranslationMessageManagerInterface $translationMessageManager;
    protected ObjectTranslationExtractor $objectTranslationExtractor;
    protected RequestStack $requestStack;

    public function __construct(TranslationManagerInterface $translationManager, TranslationMessageManagerInterface $translationMessageManager, ObjectTranslationExtractor $objectTranslationExtractor, RequestStack $requestStack)
    {
        $this->translationManager = $translationManager;
        $this->translationMessageManager = $translationMessageManager;
        $this->objectTranslationExtractor = $objectTranslationExtractor;
        $this->requestStack = $requestStack;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('sfs_trans_stats', [$this, 'translationStats']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('sfs_trans', [$this, 'translate']),
            new TwigFilter('sfs_trans_embed', [$this, 'translateEmbed']),
            new TwigFilter('sfs_trans_tags', [$this, 'translateHtmlTags'], ['is_safe' => ['html']]),
        ];
    }

    public function translate(object $entity, string $property): string
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();

        $translation = $this->translationManager->getTranslation(Keys::getTranslationKey($entity, $property), Domain::createFromClass($entity), $locale);

        return $translation instanceof TranslationMessageInterface ? $translation->getMessage() : '';
    }

    public function translateEmbed(object $entity, string $embeded, string $property): string
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();

        $translation = $this->translationManager->getTranslation(Keys::getTranslationEmbeddedKey($entity, $embeded, $property), Domain::createFromClass($entity), $locale);

        return $translation instanceof TranslationMessageInterface ? $translation->getMessage() : '';
    }

    public function translateHtmlTags(string $html, object $entity, string $field): string
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();
        $translations = Html::getTranslations($html, $entity, $field);

        $doc = new \DOMDocument();
        $doc->loadHTML($html, LIBXML_NOERROR);
        $crawler = new Crawler($doc);

        foreach ($translations as $key => $defaultValue) {
            if (!($translation = $this->translationManager->getTranslation($key, Domain::createFromClass($entity), $locale))) {
                continue;
            }

            $keyParts = explode('.', $key);
            $transId = array_pop($keyParts);
            $crawler->filter("trans#$transId")->each(function (Crawler $crawler) use ($doc, $translation) {
                $message = $translation->getMessage();
                foreach ($crawler as $node) {
                    $node->parentNode->replaceChild($doc->createTextNode($message), $node);
                }
            });
        }

        return $doc->saveHTML();
    }

    public function translationStats(object $entity, array $locales): array
    {
        $stats = [];

        $entityTranslations = $this->objectTranslationExtractor->extractTranslations($entity);

        foreach ($locales as $locale) {
            $localeTranslations = $this->translationMessageManager->getRepository()->countBy([
                'locale' => $locale,
                'translation_in' => array_keys($entityTranslations),
            ]);

            $stats[$locale] = [
                'total' => count($entityTranslations),
                'translated' => $localeTranslations,
            ];
        }

        return $stats;
    }
}

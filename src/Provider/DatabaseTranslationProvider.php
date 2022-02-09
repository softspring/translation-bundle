<?php

namespace Softspring\TranslationBundle\Provider;

use Softspring\TranslationBundle\Manager\TranslationManagerInterface;
use Symfony\Component\Translation\Provider\ProviderInterface;
use Symfony\Component\Translation\TranslatorBag;
use Symfony\Component\Translation\TranslatorBagInterface;

class DatabaseTranslationProvider implements ProviderInterface
{
    /**
     * @var TranslationManagerInterface
     */
    protected $translationManager;

    /**
     * @var string
     */
    protected $defaultLocale;

    public function __construct(TranslationManagerInterface $translationManager, string $defaultLocale)
    {
        $this->translationManager = $translationManager;
        $this->defaultLocale = $defaultLocale;
    }

    public function __toString(): string
    {
        return 'sfs://database';
    }

    public function write(TranslatorBagInterface $translatorBag): void
    {
        echo "write!!\n";

        foreach ($translatorBag->getCatalogues() as $catalogue) {
            foreach ($catalogue->getDomains() as $domain) {
                if (0 === \count($catalogue->all($domain))) {
                    continue;
                }

                foreach ($catalogue->all($domain) as $key => $value) {
                    echo sprintf("Save %s / %s (%s) : %s\n", $domain, $key, $catalogue->getLocale(), $value);
                    $this->translationManager->saveTranslation($key, $domain, $catalogue->getLocale(), $value, $catalogue->getLocale() === $this->defaultLocale, false);
                }
            }
        }

        $this->translationManager->getEntityManager()->flush();
    }

    public function read(array $domains, array $locales): TranslatorBag
    {
        $translatorBag = new TranslatorBag();
        $responses = [];

        echo "read!!\n";
//        foreach ($downloads as [$response, $locale, $domain]) {
//            if (200 !== $response->getStatusCode()) {
//                $this->logger->error(sprintf('Unable to download file content: "%s".', $response->getContent(false)));
//
//                continue;
//            }
//
//            $translatorBag->addCatalogue($this->loader->load($response->getContent(), $locale, $domain));
//        }

        return $translatorBag;
    }

    public function delete(TranslatorBagInterface $translatorBag): void
    {
        // TODO: Implement delete() method.
    }
}

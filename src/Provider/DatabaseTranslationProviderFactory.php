<?php

namespace Softspring\TranslationBundle\Provider;
use Softspring\TranslationBundle\Manager\TranslationManagerInterface;
use Symfony\Component\Translation\Exception\UnsupportedSchemeException;
use Symfony\Component\Translation\Provider\AbstractProviderFactory;
use Symfony\Component\Translation\Provider\Dsn;

/**
 * Class TranslationProviderFactory
 */
final class DatabaseTranslationProviderFactory extends AbstractProviderFactory
{
    /**
     * @var TranslationManagerInterface
     */
    protected $translationManager;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @param TranslationManagerInterface $translationManager
     * @param string                      $defaultLocale
     */
    public function __construct(TranslationManagerInterface $translationManager, string $defaultLocale)
    {
        $this->translationManager = $translationManager;
        $this->defaultLocale = $defaultLocale;
    }

    public function create(Dsn $dsn): DatabaseTranslationProvider
    {
        if ('sfs' !== $dsn->getScheme()) {
            throw new UnsupportedSchemeException($dsn, 'sfs', $this->getSupportedSchemes());
        }

        return new DatabaseTranslationProvider($this->translationManager, $this->defaultLocale);
    }

    protected function getSupportedSchemes(): array
    {
        return ['sfs'];
    }
}
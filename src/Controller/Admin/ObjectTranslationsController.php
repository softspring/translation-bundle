<?php

namespace Softspring\TranslationBundle\Controller\Admin;

use Softspring\TranslationBundle\Extractor\ObjectTranslationExtractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ObjectTranslationsController extends AbstractController
{
    protected ObjectTranslationExtractor $objectTranslationExtractor;

    /**
     * @param ObjectTranslationExtractor $objectTranslationExtractor
     */
    public function __construct(ObjectTranslationExtractor $objectTranslationExtractor)
    {
        $this->objectTranslationExtractor = $objectTranslationExtractor;
    }

    public function listTranslations(string $entity, string $entityClass, string $extendedTemplate, Request $request): Response
    {
        $entity = $this->getDoctrine()->getRepository($entityClass)->findOneById($entity);

        $translations = $this->objectTranslationExtractor->getObjectTranslations($entity);

        return $this->render('@SfsTranslation/admin/object_translations/list.html.twig', [
            'extended_template' => $extendedTemplate,
            'translations' => $translations,
            'entity' => $entity,
        ]);
    }
}
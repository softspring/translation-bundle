<?php

namespace Softspring\TranslationBundle\Manager;

use Softspring\CrudlBundle\Manager\CrudlEntityManagerInterface;
use Softspring\TranslationBundle\Model\TranslationMessageInterface;

interface TranslationMessageManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return TranslationMessageInterface
     */
    public function createEntity();

    /**
     * @param TranslationMessageInterface $entity
     */
    public function saveEntity($entity): void;

    /**
     * @param TranslationMessageInterface $entity
     */
    public function deleteEntity($entity): void;
}

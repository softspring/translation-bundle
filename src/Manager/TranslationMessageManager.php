<?php

namespace Softspring\TranslationBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\Component\CrudlController\Manager\CrudlEntityManagerTrait;
use Softspring\TranslationBundle\Model\TranslationMessageInterface;

class TranslationMessageManager implements TranslationMessageManagerInterface
{
    use CrudlEntityManagerTrait;

    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getTargetClass(): string
    {
        return TranslationMessageInterface::class;
    }
}

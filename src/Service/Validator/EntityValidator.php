<?php


namespace Quiz\Service\Validator;


use ReallyOrm\Entity\EntityInterface;

interface EntityValidator
{
    /**
     * @param EntityInterface $entity
     * @return bool
     */
    public function validate(EntityInterface $entity): bool;
}
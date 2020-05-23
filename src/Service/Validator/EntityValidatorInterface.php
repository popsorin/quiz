<?php


namespace Quiz\Service\Validator;


use ReallyOrm\Entity\EntityInterface;

interface EntityValidatorInterface
{
    /**
     * @param EntityInterface $entity
     * @return void
     */
    public function validate(EntityInterface $entity): void;
}
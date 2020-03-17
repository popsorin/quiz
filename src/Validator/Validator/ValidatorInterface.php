<?php


namespace Quiz\Validator\Validator;


use ReallyOrm\Entity\EntityInterface;

interface ValidatorInterface
{
    public static function createValidator(): ValidatorInterface;
    public function validate(EntityInterface $entity): bool;
    public function getMessageError(): string;
}
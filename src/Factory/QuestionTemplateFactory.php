<?php


namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\QuestionTemplate;
use ReflectionClass;
use ReflectionException;

class QuestionTemplateFactory extends AbstractFactory
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return QuestionTemplate::class;
    }
}
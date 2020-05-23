<?php


namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\AnswerTextInstance;

class AnswerTextInstanceFactory extends AbstractFactory
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return AnswerTextInstance::class;
    }
}
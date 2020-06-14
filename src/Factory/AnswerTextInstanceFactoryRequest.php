<?php


namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\AnswerTextInstance;

class AnswerTextInstanceFactoryRequest extends RequestAbstractFactory
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return AnswerTextInstance::class;
    }
}
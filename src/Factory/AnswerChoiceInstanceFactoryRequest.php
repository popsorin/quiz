<?php

namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\AnswerChoiceInstance;

class AnswerChoiceInstanceFactoryRequest extends RequestAbstractFactory
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return AnswerChoiceInstance::class;
    }
}
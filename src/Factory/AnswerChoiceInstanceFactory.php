<?php

namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\AnswerChoiceInstance;

class AnswerChoiceInstanceFactory extends AbstractFactory
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return AnswerChoiceInstance::class;
    }
}
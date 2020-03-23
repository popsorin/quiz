<?php

namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\AnswerChoiceInstance;

class AnswerChoiceInstanceFactory
{
    /**
     * @param Request $request
     * @param string $textKey
     * @param string $questionInstanceIdKey
     * @param string $isSelectedKey
     * @param string $isCorrectKey
     * @return AnswerChoiceInstance
     */
    public function createFromRequest(
        Request $request,
        string $textKey,
        string $questionInstanceIdKey,
        string $isSelectedKey,
        string $isCorrectKey
    ) {
        $parameters = $request->getParameters();
        $questionInstanceId = 0;
        $text = "";
        $isSelected = false;
        $isCorrect = false;
        if(array_key_exists($questionInstanceIdKey, $parameters)) {
            $questionInstanceId = $parameters[$questionInstanceIdKey];
        }
        if(array_key_exists($textKey, $parameters)) {
            $text = $parameters[$textKey];
        }
        if(array_key_exists($isSelectedKey, $parameters)) {
            $isSelected = $parameters[$isSelectedKey];
        }
        if(array_key_exists($isCorrectKey, $parameters)) {
            $isCorrect = $parameters[$isCorrectKey];
        }

        return new AnswerChoiceInstance($text, $questionInstanceId, $isSelected, $isCorrect);
    }
}
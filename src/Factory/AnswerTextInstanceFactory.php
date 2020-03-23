<?php


namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\AnswerTextInstance;

class AnswerTextInstanceFactory
{
    /**
     * @param Request $request
     * @param string $questionInstanceIdKey
     * @param string $textKey
     * @return AnswerTextInstance
     */
    public function createFromRequest(
        Request $request,
        string $questionInstanceIdKey,
        string $textKey
    ): AnswerTextInstance {
        $parameters = $request->getParameters();
        $questionInstanceId = 0;
        $text = "";
        if(array_key_exists($questionInstanceIdKey, $parameters)) {
            $questionInstanceId = $parameters[$questionInstanceIdKey];
        }
        if(array_key_exists($textKey, $parameters)) {
            $text = $parameters[$textKey];
        }

        return new AnswerTextInstance($questionInstanceId, $text);
    }
}
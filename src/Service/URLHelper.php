<?php


namespace Quiz\Service;


class URLHelper
{
    /**
     * @param ParameterBag $parameterBag
     * @return string
     */
    public function buildURLQuery(ParameterBag $parameterBag): string
    {
        if($parameterBag->count() === 0) {
            return "";
        }

        $url = "";
        $parameterBag = $parameterBag->getParameters();
        foreach ($parameterBag as $parameter) {
            $url = sprintf(
                "&%s=%s:%s&",
                $parameter->getOperation(),
                $parameter->getField(),
                $parameter->getValue()
            );
        }
        $url = substr($url, 0, -1);

        return $url;
    }
}
<?php


namespace Quiz\Service;


class URLHelper
{
    /**
     * @param RequestParameterBag $requestParameterBag
     * @return string
     */
    public function buildURLQuery(RequestParameterBag $requestParameterBag): string
    {
        if($requestParameterBag->count() === 0) {
            return "";
        }

        $url = "";
        foreach ($requestParameterBag->getParameters() as $parameter) {
            $operation = $parameter->getOperation();
            $url = sprintf(
                "$url%s=%s:%s&",
                $operation ,
                $parameter->getField(),
                $parameter->getValue()
            );
        }
        $url = substr($url, 0, -1);

        return $url;
    }
}
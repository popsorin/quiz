<?php


namespace Quiz\Service;


class URLHelper
{
    /**
     * @param ParameterBag $parameterBagService
     * @return string
     */
    public function buildURLQuery(ParameterBag $parameterBagService): string
    {
        if($parameterBagService->count() === 0) {
           return "";
        }

        $url = "&";
        $parameterBag = $parameterBagService->getParameters();
        foreach ($parameterBag as $key=>$value) {
            $url .= "$key=$value&";
        }

        $url = substr($url, 0, -1);

        return $url;
    }
}
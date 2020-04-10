<?php


namespace Quiz\Service;


class URLHelper
{
    /**
     * @param ParameterBagService $parameterBagService
     * @return string
     */
    public function buildURLQuery(ParameterBagService $parameterBagService): string
    {
        if($parameterBagService->count() === 0) {
           return "";
        }

        $url = "&";
        $parameterBag = $parameterBagService->getParameterBag();
        foreach ($parameterBag as $key=>$value) {
            $url .= "$key=$value&";
        }

        $url = substr($url, 0, strlen($url)-1);

        return $url;
    }
}
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

        $url = "&";
        $parameterBag = $parameterBag->getParameters();
        foreach ($parameterBag as $key=>$value) {
            $url .= "$key=$value&";
        }

        $url = substr($url, 0, -1);

        return $url;
    }
}
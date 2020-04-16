<?php


namespace Quiz\Service;


class URLHelper
{
    /**
     *
     * Builds the query from the parameter bag for sort,filter and search without the
     * query variables of the operation that comes from the request.This is done
     * because the query variables are hard coded in the html
     *
     *
     * @param RequestParameterBag $requestParameterBag
     * @param string $omitOperation
     * @return string
     */
    public function buildURLQuery(RequestParameterBag $requestParameterBag, string $omitOperation): string
    {
        if($requestParameterBag->count() === 0) {
            return "";
        }

        $url = "";
        foreach ($requestParameterBag->getParameters() as $parameter) {
            $operation = $parameter->getOperation();
            if($operation === $omitOperation) {
                continue;
            }
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
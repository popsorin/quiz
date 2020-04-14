<?php


namespace Quiz\Service;


class RequestParameterBag extends ParameterBag
{
    const OPERATIONS = ["search", "sort", "filter"];

    /**
     * ParameterBag constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = self::createFromRequestParameters($parameters);
    }

    /**
     *
     * Creates the parameter bag from the request
     * This will be used for building the URL when the admin will search,sort or filter
     *
     * @param array $parameters
     * @return array
     */
    private static function createFromRequestParameters(array $parameters): array
    {
        $results = [];
        foreach (self::OPERATIONS as $operation) {
            if ($parameters[$operation] && $parameters[$operation] !== "") {
                $explode = explode(":", $parameters[$operation]);

                $results[] = new Parameter($operation, $explode[0], $explode[1]);
            }
        }

        return $results;
    }

    /**
     *
     * Returns a array with the field and value for searching
     *
     * @return array
     */
    public function getSearchParameters()
    {
        $results = [];
        foreach ($this->parameters as $parameter) {
            if ($parameter->getOperation() === "search") {
                $results = [$parameter->getField() => $parameter->getValue()];
            }
        }

        return $results;
    }

    /**
     *
     * Returns a array with the field and value for sorting
     *
     * @return array
     */
    public function getSortParameters()
    {
        $results = [];
        foreach ($this->parameters as $parameter) {
            if ($parameter->getOperation() === "sort") {
                $results = [$parameter->getField() => $parameter->getValue()];
            }
        }

        return $results;
    }

    /**
     *
     * Returns a array with the field and value for filtering
     *
     * @return array
     */
    public function getFilterParameters(): array
    {
        $result = [];
        foreach ($this->parameters as $parameter) {
            if ($parameter->getOperation() === "filter") {
                $result[$parameter->getField()] = $parameter->getValue();
            }
        }

        return $result;
    }
}
<?php


namespace Quiz\Service;


use Quiz\Service\Parameter;

class ParameterBag
{
    const OPERATIONS = ["search", "sort", "filter"];

    /**
     * @var array
     */
    private $parameters;

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
            if($parameters[$operation] !== null && $parameters[$operation] !== "") {
                $explode = explode(":", $parameters[$operation]);

                $results[] = new Parameter($operation, $explode[0], $explode[1]);
            }
        }

        return $results;
    }

    /**
     * @param array $parameters
     */
    public function addParameter(array $parameters = []): void
    {
        $this->parameters = array_replace($this->parameters, $parameters);
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getParameter(string $key): ?string
    {
      return ($this->parameters[$key]) ?? null;
    }

    /**
     *
     * Returns a array with the objects for filtering
     *
     * @return array
     */
    public function getFilterParameters(): array
    {
        $result = [];
        foreach ($this->parameters as $parameter) {
            if($parameter->getOperation() === "filter") {
                $result[$parameter->getField()] = $parameter->getValue();
            }
        }

        return $result;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->parameters);
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
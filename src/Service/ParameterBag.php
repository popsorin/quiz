<?php


namespace Quiz\Service;


class ParameterBag
{
    const OPERATIONS = ["search", "sort", "role"];

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
            if($parameters[$operation] && $parameters[$operation] !== "") {
                $results[$operation] = $parameters[$operation];
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
        if(array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }

        return null;
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
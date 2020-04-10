<?php


namespace Quiz\Service;


class ParameterBagService
{
    const OPERATIONS = ["search", "sort", "role"];

    /**
     * @var array
     */
    private $parameterBag;

    /**
     * ParameterBagService constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameterBag = self::createFromRequestParameters($parameters);
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
            if(isset($parameters[$operation]) && $parameters[$operation] !== "") {
                $results[$operation] = $parameters[$operation];
            }
        }

        return $results;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getParameter(string $key): ?string
    {
        return $this->parameterBag[$key];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->parameterBag);
    }

    public function getParameterBag(): array
    {
        return $this->parameterBag;
    }
}
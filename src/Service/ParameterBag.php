<?php


namespace Quiz\Service;


use Quiz\Service\Parameter;

class ParameterBag
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * ParameterBag constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
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
<?php


namespace Quiz\Service;


use Quiz\Service\Parameter;

abstract class AbstractParameterBag
{
    /**
     * @var array
     */
    protected $parameters;

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
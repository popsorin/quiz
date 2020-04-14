<?php


namespace Quiz\Service;


class Parameter
{
    /**
     * @var string
     */
    private $operation;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $value;

    /**
     * Parameter constructor.
     * @param string $operation
     * @param string $field
     * @param string $value
     */
    public function __construct(string $operation, string $field, string $value)
    {
        $this->operation = $operation;
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * @param string $operation
     */
    public function setOperation(string $operation): void
    {
        $this->operation = $operation;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
<?php


namespace Quiz\Entity;


use ReallyOrm\Entity\AbstractEntity;

class QuestionTemplate extends AbstractEntity
{
    /**
     * @var string
     * @MappedOn text
     */
    private $text;

    /**
     * @var string
     * @MappedOn type
     */
    private $type;

    const ALLOWED_TYPES = [
      'text'
    ];

    /**
     * QuestionTemplate constructor.
     * @param string $text
     * @param string $type
     */
    public function __construct(string $text, string $type)
    {
        $this->text = $text;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
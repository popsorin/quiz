<?php


namespace Quiz\Entity;


use ReallyOrm\Entity\AbstractEntity;

class QuestionTemplate extends AbstractEntity
{

    /**
     * @var string
     * @MappedOn answer
     */
    private $answer;
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
     * @param string $answer
     * @param string $text
     * @param string $type
     */
    public function __construct(string $answer, string $text, string $type)
    {
        $this->answer = $answer;
        $this->text = $text;
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     * @return self
     */
    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
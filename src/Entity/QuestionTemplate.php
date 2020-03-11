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
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
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
     */
    public function setText(string $text): void
    {
        $this->text = $text;
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
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
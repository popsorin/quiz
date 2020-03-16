<?php

namespace Quiz\Entity;

use ReallyOrm\Entity\EntityInterface;

class TextAnswerInstance implements EntityInterface
{
    /**
     * @var int
     */
    private $questionInstanceId;

    /**
     * @var string
     */
    private $text;

    public function __construct(int $questionInstanceId, string $text)
    {
        $this->questionInstanceId = $questionInstanceId;
        $this->text = $text;
    }

    public function getQuestionInstanceId() : ?int
    {
        return $this->questionInstanceId;
    }

    public function setQuestionInstanceInterface(int $questionInstanceId) :TextAnswerInstance
    {
        $this->questionInstanceId = $questionInstanceId;

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
     * @return TextAnswerInstance
     */
    public function setText(string $text): TextAnswerInstance
    {
        $this->text = $text;

        return $this;
    }
}
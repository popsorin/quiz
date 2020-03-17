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

    /**
     * @return int|null
     */
    public function getQuestionInstanceId() : ?int
    {
        return $this->questionInstanceId;
    }

    /**
     * @param int $questionInstanceId
     * @return self
     */
    public function setQuestionInstanceInterface(int $questionInstanceId): self
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
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
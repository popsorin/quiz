<?php

namespace Quiz\Entity;

use ReallyOrm\Entity\AbstractEntity;

class AnswerTextInstance extends AbstractEntity
{
    /**
     * @var int
     * @MappedOn question_instance_id
     */
    private $questionInstanceId;

    /**
     * @var string
     * @MappedOn text
     */
    private $text;

    /**
     * AnswerTextInstance constructor.
     * @param int $questionInstanceId
     * @param string $text
     */
    public function __construct(int $questionInstanceId, string $text)
    {
        $this->questionInstanceId = $questionInstanceId;
        $this->text = $text;
    }

    /**
     * @return int|null
     */
    public function getQuestionInstanceId(): int
    {
        return $this->questionInstanceId;
    }

    /**
     * @param int $questionInstanceId
     * @return AnswerTextInstance
     */
    public function setQuestionInstanceId(int $questionInstanceId): self
    {
        $this->questionInstanceId = $questionInstanceId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return AnswerTextInstance
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
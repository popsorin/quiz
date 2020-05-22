<?php

namespace Quiz\Entity;

use ReallyOrm\Entity\AbstractEntity;

class AnswerTextTemplate extends AbstractEntity
{

    /**
     * @var int
     * @MappedOn question_template_id
     *
     */
    private $questionTemplateId;

    /**
     * @var string
     * @MappedOn text
     */
    private $text;

    /**
     * AnswerTextInstance constructor.
     * @param int $questionTemplateId
     * @param string $text
     */
    public function __construct(int $questionTemplateId, string $text)
    {
        $this->questionTemplateId = $questionTemplateId;
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getQuestionTemplateId(): int
    {
        return $this->questionTemplateId;
    }

    /**
     * @param int $questionTemplateId
     * @return AnswerTextTemplate
     */
    public function setQuestionTemplateId(int $questionTemplateId): self
    {
        $this->questionTemplateId = $questionTemplateId;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return AnswerTextTemplate
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return AnswerTextTemplate::class;
    }
}
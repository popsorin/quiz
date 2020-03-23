<?php

namespace Quiz\Entity;

use ReallyOrm\Entity\AbstractEntity;

class AnswerChoiceTemplate extends AbstractEntity
{
    /**
     * @var int
     * @MappedOn question_template_id
     */
    private $questionTemplateId;

    /**
     * @var string
     * @MappedOn text
     */
    private $text;

    /**
     * @var bool
     * @MappedOn is_correct
     */
    private $isCorrect;

    /**
     * AnswerChoiceTemplate constructor.
     * @param int $questionTemplateId
     * @param string $text
     * @param bool $isCorrect
     */
    public function __construct(int $questionTemplateId, string $text, bool $isCorrect)
    {
        $this->questionTemplateId = $questionTemplateId;
        $this->text = $text;
        $this->isCorrect = $isCorrect;
    }

    /**
     * @return int|null
     */
    public function getQuestionTemplateId(): ?int
    {
        return $this->questionTemplateId;
    }

    /**
     * @param int $questionTemplateId
     * @return AnswerChoiceTemplate
     */
    public function setQuestionTemplateId(int $questionTemplateId): self
    {
        $this->questionTemplateId = $questionTemplateId;
        
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
     * @return AnswerChoiceTemplate
     */
    public function setText(string $text): self 
    {
        $this->text = $text;
        
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    /**
     * @param bool $isCorrect
     * @return AnswerChoiceTemplate
     */
    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }
}
<?php

namespace Quiz\Entity;

use ReallyOrm\Entity\AbstractEntity;

class AnswerChoiceInstance extends AbstractEntity
{
    /**
     * @var string
     * @MappedOn text
     */
    private $text;

    /**
     * @var int
     * @MappedOn question_instance_id
     */
    private $questionInstanceId;

    /**
     * @var bool
     * @MappedOn isSelected
     */
    private $isSelected;

    /**
     * @var bool
     * @MappedOn isCorrect
     */
    private $isCorrect;

    /**
     * AnswerChoiceInstance constructor.
     * @param string $text
     * @param int $questionInstanceId
     * @param bool $isSelected
     * @param bool $isCorrect
     */
    public function __construct(string $text, int $questionInstanceId, bool $isSelected, bool $isCorrect)
    {
        $this->text = $text;
        $this->questionInstanceId = $questionInstanceId;
        $this->isSelected = $isSelected;
        $this->isCorrect = $isCorrect;
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
     * @return AnswerChoiceInstance
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuestionInstanceId(): ?int
    {
        return $this->questionInstanceId;
    }

    /**
     * @param int $questionInstanceId
     * @return AnswerChoiceInstance
     */
    public function setQuestionInstanceId(int $questionInstanceId): self
    {
        $this->questionInstanceId = $questionInstanceId;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isSelected(): ?bool
    {
        return $this->isSelected;
    }

    /**
     * @param bool $isSelected
     * @return AnswerChoiceInstance
     */
    public function setIsSelected(bool $isSelected): self
    {
        $this->isSelected = $isSelected;

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
     * @return AnswerChoiceInstance
     */
    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }
}
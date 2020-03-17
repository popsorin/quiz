<?php


namespace Quiz\Entity;


use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\EntityInterface;

class QuestionInstance extends AbstractEntity
{
    /**
     * @var string
     * @MappedOn text
     */
    private $text;

    /**
     * @var int
     * @MappedOn quiz_instance_id
     */
    private $quizInstanceId;

    /**
     * @var string
     * @MappedOn type
     */
    private $type;

    /**
     * @var int
     * @MappedOn question_template_id
     */
    private $questionTemplateId;

    /**
     * @var string
     * @MappedOn answer
     */
    private $answer;

    /**
     * QuestionInstance constructor.
     * @param string $text
     * @param int $quizInstanceId
     * @param string $type
     * @param int $questionTemplateId
     * @param string $answer
     */
    public function __construct(string $text, int $quizInstanceId, string $type, int $questionTemplateId, string $answer)
    {
        $this->text = $text;
        $this->quizInstanceId = $quizInstanceId;
        $this->type = $type;
        $this->questionTemplateId = $questionTemplateId;
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
     * @return EntityInterface
     */
    public function setText(string $text): EntityInterface
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuizInstanceId(): int
    {
        return $this->quizInstanceId;
    }

    /**
     * @param int $quizInstanceId
     * @return EntityInterface
     */
    public function setQuizInstanceId(int $quizInstanceId): EntityInterface
    {
        $this->quizInstanceId = $quizInstanceId;

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
     * @return EntityInterface
     */
    public function setType(string $type): EntityInterface
    {
        $this->type = $type;

        return $this;
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
     * @return EntityInterface
     */
    public function setQuestionTemplateId(int $questionTemplateId): EntityInterface
    {
        $this->questionTemplateId = $questionTemplateId;

        return $this;
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
     * @return EntityInterface
     */
    public function setAnswer(string $answer): EntityInterface
    {
        $this->answer = $answer;

        return $this;
    }
}
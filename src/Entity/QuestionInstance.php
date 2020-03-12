<?php


namespace Quiz\Entity;


use ReallyOrm\Entity\AbstractEntity;

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
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
     * @return int
     */
    public function getQuizInstanceId(): int
    {
        return $this->quizInstanceId;
    }

    /**
     * @param int $quizInstanceId
     */
    public function setQuizInstanceId(int $quizInstanceId): void
    {
        $this->quizInstanceId = $quizInstanceId;
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

    /**
     * @return int
     */
    public function getQuestionTemplateId(): int
    {
        return $this->questionTemplateId;
    }

    /**
     * @param int $questionTemplateId
     */
    public function setQuestionTemplateId(int $questionTemplateId): void
    {
        $this->questionTemplateId = $questionTemplateId;
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
}
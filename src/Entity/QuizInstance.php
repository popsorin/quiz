<?php


namespace Quiz\Entity;


use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\self;

class QuizInstance extends AbstractEntity
{
    /**
     * @var int
     * @MappedOn quiz_template_id
     */
    private $quizId;
    /**
     * @var int
     * @MappedOn user_id
     */
    private $userId;
    /**
     * @var int
     * @MappedOn score
     */
    private $score;

    /**
     * @var string
     * @MappedOn name
     */
    private $name;

    /**
     * @var string
     * @MappedOn description
     */
    private $description;

    /**
     * @var int
     * @MappedOn nrQuestions
     */
    private $nrQuestions;

    /**
     * QuizInstance constructor.
     * @param int $quizId
     * @param int $userId
     * @param int $score
     * @param string $name
     * @param string $description
     * @param int $nrQuestions
     */
    public function __construct(int $quizId, int $userId, int $score, string $name, string $description, int $nrQuestions)
    {
        $this->quizId = $quizId;
        $this->userId = $userId;
        $this->score = $score;
        $this->name = $name;
        $this->description = $description;
        $this->nrQuestions = $nrQuestions;
    }

    /**
     * @return int
     */
    public function getQuizId(): int
    {
        return $this->quizId;
    }

    /**
     * @param int $quizId
     * @return self
     */
    public function setQuizId(int $quizId): self
    {
        $this->quizId = $quizId;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return self
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     * @return self
     */
    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getNrQuestions(): int
    {
        return $this->nrQuestions;
    }

    /**
     * @param int $nrQuestions
     * @return self
     */
    public function setNrQuestions(int $nrQuestions): self
    {
        $this->nrQuestions = $nrQuestions;

        return $this;
    }
}
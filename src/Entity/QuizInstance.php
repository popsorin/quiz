<?php


namespace Quiz\Entity;


use http\Exception;
use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\EntityInterface;

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
     * @return EntityInterface
     */
    public function setQuizId(int $quizId): EntityInterface
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
     * @return EntityInterface
     */
    public function setUserId(int $userId): EntityInterface
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
     * @return EntityInterface
     */
    public function setScore(int $score): EntityInterface
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
     * @return EntityInterface
     */
    public function setName(string $name): EntityInterface
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
     * @return EntityInterface
     */
    public function setDescription(string $description): EntityInterface
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
     * @return EntityInterface
     */
    public function setNrQuestions(int $nrQuestions): EntityInterface
    {
        $this->nrQuestions = $nrQuestions;

        return $this;
    }
}
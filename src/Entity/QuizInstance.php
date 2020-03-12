<?php


namespace Quiz\Entity;


use ReallyOrm\Entity\AbstractEntity;

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
     * @return int
     */
    public function getQuizId(): int
    {
        return $this->quizId;
    }

    /**
     * @param int $quizId
     */
    public function setQuizId(int $quizId): void
    {
        $this->quizId = $quizId;
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
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
     */
    public function setNrQuestions(int $nrQuestions): void
    {
        $this->nrQuestions = $nrQuestions;
    }
}
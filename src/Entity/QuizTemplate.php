<?php

namespace Quiz\Entity;

use ReallyOrm\Entity\AbstractEntity;

class QuizTemplate extends AbstractEntity
{
    /**
     * @var int
     * @MappedOn created_by
     */
    private $createdBy;

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
     * @MappedOn
     */
    private $nrQuestions;

    /**
     * QuizTemplate constructor.
     * @param int $createdBy
     * @param string $name
     * @param string $description
     * @param int $nrQuestions
     */
    public function __construct(int $createdBy, string $name, string $description, int $nrQuestions)
    {
        $this->createdBy = $createdBy;
        $this->name = $name;
        $this->description = $description;
        $this->nrQuestions = $nrQuestions;
    }

    /**
     * @return int
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     * @param int $createdBy
     */
    public function setCreatedBy(int $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return string
     */
    public function getName(): ?string
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
    public function getDescription(): ?string
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
     * @return int|null
     */
    public function getNrQuestions(): ?int
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
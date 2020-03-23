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
     * @MappedOn nrQuestions
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return self
     */
    public function setCreatedBy(int $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
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
    public function getDescription(): ?string
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
     * @return int|null
     */
    public function getNrQuestions(): ?int
    {
        return $this->nrQuestions;
    }

    /**
     * @param int $nrQuestions
     * @return self|null

     */
    public function setNrQuestions(int $nrQuestions): self
    {
        $this->nrQuestions = $nrQuestions;

        return $this;
    }
}
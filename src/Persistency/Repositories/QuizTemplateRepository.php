<?php

namespace Quiz\Persistency\Repositories;

use PDO;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuizTemplateRepository extends AbstractRepository
{
    /**
     * QuizTemplateRepository constructor.
     * @param PDO $pdo
     * @param string $entityName
     * @param HydratorInterface $hydrator
     * @param string $tableName
     */
    public function __construct(
        PDO $pdo,
        string $entityName,
        HydratorInterface $hydrator,
        string $tableName
    ) {
        parent::__construct($pdo, $entityName, $hydrator);
        $this->tableName = $tableName;
    }

    public function insertIntoLinkTable(int $id, int $linkId)
    {
        $query = $this->pdo->prepare(
            "INSERT INTO {$this->getLinkTableName()} (question_template_id, quiz_template_id) VALUES (?, ?)");
        $query->bindValue(1, $id);
        $query->bindValue(2, $linkId);
        return $query->execute();
    }

    /**
     * @param int $quizId
     */
    public function deleteQuestions(int $quizId)
    {
        $query = $this->pdo->prepare("DELETE FROM " . $this->getLinkTableName() . " WHERE quiz_template_id = ?");
        $query->bindValue(1, $quizId);
        $query->execute();
    }

    /**
     * Returns the name of the link table.
     *
     * @return string
     */
    public function getLinkTableName(): string
    {
        return 'quiz_question_template';
    }
}
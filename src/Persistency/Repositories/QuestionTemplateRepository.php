<?php

namespace Quiz\Persistency\Repositories;

use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuestionTemplateRepository extends AbstractRepository implements LinkedEntityInterface
{
    public function __construct(
        \PDO $pdo,
        string $entityName,
        HydratorInterface $hydrator,
        string $tableName
    )
    {
        parent::__construct($pdo, $entityName, $hydrator);
        $this->tableName = $tableName;
    }

    /**
     * @param int $id
     * @param int $linkId
     * @return bool
     */
    public function insertIntoLinkTable(int $id, int $linkId)
    {
        $query = $this->pdo->prepare(
            "INSERT INTO {$this->getLinkTableName()} (question_template_id, quiz_template_id) VALUES (?, ?)");
        $query->bindValue(1, $id);
        $query->bindValue(2, $linkId);
        return $query->execute();
    }

    public function getQuestions(?int $id)
    {
        $query = $this->pdo->prepare(
            "SELECT (question_template_id) FROM {$this->getLinkTableName()} WHERE quiz_template_id = ?");
        $query->bindValue(1, $id);
        $query->execute();
        while ($row = $query->fetch()) {
            $result[] = $row['question_template_id'];
        }

        return $result;
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
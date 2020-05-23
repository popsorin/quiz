<?php

namespace Quiz\Persistency\Repositories;

use PDO;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuestionTemplateRepository extends AbstractRepository implements LinkedEntityInterface
{
    /**
     * QuestionTemplateRepository constructor.
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
    public function insertIntoLinkTable(int $id, int $linkId): bool
    {
        $query = $this->pdo->prepare(
            "INSERT INTO {$this->getLinkTableName()} (questionId, quizId) VALUES (?, ?)");
        $query->bindValue(1, $id);
        $query->bindValue(2, $linkId);

        return $query->execute();
    }

    /**
     * @param int|null $id
     * @return array
     */
    public function getQuestionIds(?int $id): array
    {
        $query = $this->pdo->prepare(
            "SELECT (questionId) FROM {$this->getLinkTableName()} WHERE quizId = ?");
        $query->bindValue(1, $id);
        $query->execute();
        $result = [];
        while ($row = $query->fetch()) {
            $result[] = $row['questionId'];
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
        return 'QuestionQuizTemplate';
    }
}
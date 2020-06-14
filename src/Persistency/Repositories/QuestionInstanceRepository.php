<?php

namespace Quiz\Persistency\Repositories;

use PDO;
use Quiz\Entity\QuestionInstance;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuestionInstanceRepository extends AbstractRepository
{
    /**
     * QuestionInstanceRepository constructor.
     * @param PDO $pdo
     * @param string $entityName
     * @param HydratorInterface $hydrator
     * @param string $tableName
     */
    public function __construct(PDO $pdo, string $entityName, HydratorInterface $hydrator, string $tableName)
    {
        parent::__construct($pdo, $entityName, $hydrator);
        $this->tableName = $tableName;
    }

    /**
     * @param int $quizId
     * @return array
     */
    public function getQuestionsInstances(int $quizId): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE quizInstanceId = ?");
        $statement->bindParam(1, $quizId);
        $statement->execute();

        $results = [];
        while($row = $statement->fetch()) {
            $entity = $this->hydrator->hydrate(QuestionInstance::class, $row);
            $results[] = $entity;
        }

        return $results;
    }

}
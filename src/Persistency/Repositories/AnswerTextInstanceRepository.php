<?php


namespace Quiz\Persistency\Repositories;


use PDO;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\AbstractRepository;

class AnswerTextInstanceRepository extends AbstractRepository
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
     * @param int $questionInstanceId
     * @return array
     */
    public function getAnswersTextInstances(int $questionInstanceId): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE questionInstanceId = ?");
        $statement->bindParam(1, $questionInstanceId);
        $statement->execute();

        $results = [];
        while($row = $statement->fetch()) {
            $results = array_merge($results, $row);
        }

        return $results;
    }
}
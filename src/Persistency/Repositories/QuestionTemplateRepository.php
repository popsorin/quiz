<?php


namespace Quiz\Persistency\Repositories;


use PDO;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\AbstractRepository;

class QuestionTemplateRepository extends AbstractRepository
{
    /**
     * change AbstractRepository in orm
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
    ) {
        parent::__construct($pdo, $entityName, $hydrator);
        $this->tableName = $tableName;
    }

    /**
     * @param int $idQuestion
     * @param int $idQuiz
     * @param string $linkedTable
     */
    public function insertIntoLinkTable(int $idQuestion, int $idQuiz, string $linkedTable)
    {
        $query = $this->pdo->prepare("INSERT INTO $linkedTable (id, id) VALUES (id = :idQuestion, id = :idQuiz)");
        $query->bindParam(":idQuestion", $idQuestion);
        $query->bindParam(":idQuiz", $idQuiz);
        $query->execute();
    }
}
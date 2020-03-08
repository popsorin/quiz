<?php

namespace Quiz\Persistency\Repositories;

use PDO;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\AbstractRepository;

class LoginRepository extends AbstractRepository
{
    /**
     * LoginRepository constructor.
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
}
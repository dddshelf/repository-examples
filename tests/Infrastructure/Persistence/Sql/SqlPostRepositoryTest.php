<?php

namespace Infrastructure\Persistence\Doctrine;

require_once __DIR__ . '/../PersistentPostRepositoryTest.php';

use Infrastructure\Persistence\Sql\SqlLatestPostSpecification;
use Infrastructure\Persistence\Sql\SqlPostRepository;

class SqlPostRepositoryTest extends \PersistentPostRepositoryTest
{
    protected function createPostRepository()
    {
        $pdo = new \PDO('sqlite::memory:');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $repository =  new SqlPostRepository($pdo);
        $repository->initSchema();

        return $repository;
    }

    protected function createLatestPostSpecification(\DateTime $since)
    {
        return new SqlLatestPostSpecification($since);
    }
}

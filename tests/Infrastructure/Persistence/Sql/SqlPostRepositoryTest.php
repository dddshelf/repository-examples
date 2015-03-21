<?php

namespace Infrastructure\Persistence\Doctrine;

require_once __DIR__ . '/../PostRepositoryTest.php';

use Infrastructure\Persistence\Sql\SqlPostRepository;

class SqlPostRepositoryTest extends \PostRepositoryTest
{
    protected function createPostRepository()
    {
        $pdo = new \PDO('sqlite::memory:');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $repository =  new SqlPostRepository($pdo);
        $repository->initSchema();

        return $repository;
    }
}

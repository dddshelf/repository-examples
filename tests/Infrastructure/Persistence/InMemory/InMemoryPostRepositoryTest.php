<?php

namespace Infrastructure\Persistence\Doctrine;

require_once __DIR__ . '/../PostRepositoryTest.php';

use Infrastructure\Persistence\InMemory\InMemoryPostRepository;

class InMemoryPostRepositoryTest extends \PostRepositoryTest
{
    protected function createPostRepository()
    {
        return new InMemoryPostRepository();
    }
}
